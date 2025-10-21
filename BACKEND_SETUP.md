# Backend Setup Guide - Laravel + Hybrid Telemetry

This guide walks you through setting up the Laravel backend with the hybrid architecture recommended for GPS tracking.

## 📋 Architecture Overview

```
┌─────────────────────────────────────────────────────────────┐
│                      React Frontend                          │
│          (Dashboard, Assets, Geofencing, etc.)              │
└─────────────────┬───────────────────────────────────────────┘
                  │ REST API + WebSockets
┌─────────────────▼───────────────────────────────────────────┐
│                   Laravel Backend                            │
│  (Admin Panel, APIs, Auth, Reports, Notifications)          │
│  • Orchid Platform                                           │
│  • Sanctum Auth                                              │
│  • Spatie Permissions                                        │
│  • Laravel Horizon (Queues)                                  │
│  • Laravel WebSockets                                        │
└─┬───────────────────────────────────────────────────────┬───┘
  │                                                         │
  │ ┌─────────────────────────────────────────────────┐  │
  ├─┤ PostgreSQL + PostGIS                             │  │
  │ │ • Organizations, Users, Devices                  │  │
  │ │ • Assets, Drivers, Geofences                     │  │
  │ │ • Alert Rules, Webhooks                          │  │
  │ └─────────────────────────────────────────────────┘  │
  │                                                         │
  │ ┌─────────────────────────────────────────────────┐  │
  ├─┤ TimescaleDB (Hypertable)                         │  │
  │ │ • Positions (high-volume telemetry)              │  │
  │ │ • Sensor readings, trips                         │  │
  │ └─────────────────────────────────────────────────┘  │
  │                                                         │
  │ ┌─────────────────────────────────────────────────┐  │
  └─┤ Redis                                             │  │
    │ • Queues, Cache, Sessions                        │  │
    │ • Last known device states                       │  │
    └─────────────────────────────────────────────────┘  │
                                                           │
┌──────────────────────────────────────────────────────────▼─┐
│            Kafka / NATS (Message Bus)                       │
│  Topics: positions, events, alerts, device-status           │
└──────────────┬──────────────────────────────────────────────┘
               │
┌──────────────▼──────────────────────────────────────────────┐
│         GPS Ingestion Service                                │
│  • Traccar (Java) - Recommended                              │
│  • OR Custom Go/Kotlin service                               │
│  • Handles 100+ GPS protocols                                │
└──────────────────────────────────────────────────────────────┘
```

---

## 🚀 Quick Start

### 1. Install Required Packages

```bash
# Install Laravel WebSockets dependencies
npm install --save laravel-echo pusher-js axios
```

### 2. Environment Configuration

Create `.env.local` in your React app root:

```env
REACT_APP_API_URL=http://localhost:8000/api/v1
REACT_APP_WS_HOST=localhost
REACT_APP_WS_PORT=6001
REACT_APP_WS_KEY=gps-track-key
REACT_APP_ENABLE_WEBSOCKETS=true
```

### 3. Laravel Backend Setup

```bash
# Create new Laravel project
composer create-project laravel/laravel gps-track-backend
cd gps-track-backend

# Install core packages
composer require orchid/platform
composer require spatie/laravel-permission
composer require laravel/sanctum
composer require mstaack/laravel-postgis
composer require laravel/horizon
composer require predis/predis
composer require beyondcode/laravel-websockets
composer require maatwebsite/excel
composer require spatie/laravel-activitylog

# Optional but recommended
composer require laravel/scout meilisearch/meilisearch-laravel
composer require laravel/cashier
composer require spatie/laravel-multitenancy

# Publish configurations
php artisan vendor:publish --provider="Orchid\Platform\Providers\FoundationServiceProvider"
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider"

# Run migrations
php artisan migrate
php artisan orchid:install
```

---

## 📊 Database Schema

### Core Tables (PostgreSQL + PostGIS)

#### Organizations (Multi-tenancy)
```sql
CREATE TABLE organizations (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) UNIQUE,
  settings JSONB,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### Devices
```sql
CREATE TABLE devices (
  id BIGSERIAL PRIMARY KEY,
  organization_id BIGINT REFERENCES organizations(id),
  imei VARCHAR(20) UNIQUE NOT NULL,
  name VARCHAR(255),
  protocol VARCHAR(50),
  sim_number VARCHAR(20),
  status VARCHAR(20) DEFAULT 'active',
  settings JSONB,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
CREATE INDEX idx_devices_org ON devices(organization_id);
CREATE INDEX idx_devices_imei ON devices(imei);
```

#### Assets
```sql
CREATE TABLE assets (
  id BIGSERIAL PRIMARY KEY,
  organization_id BIGINT REFERENCES organizations(id),
  device_id BIGINT REFERENCES devices(id),
  name VARCHAR(255),
  type VARCHAR(50), -- truck, van, car, crane, etc.
  plate_number VARCHAR(20),
  odometer INT DEFAULT 0,
  meta JSONB,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### Geofences (PostGIS)
```sql
CREATE TABLE geofences (
  id BIGSERIAL PRIMARY KEY,
  organization_id BIGINT REFERENCES organizations(id),
  name VARCHAR(255),
  area GEOMETRY(POLYGON, 4326), -- PostGIS polygon
  color VARCHAR(7),
  alerts_enabled BOOLEAN DEFAULT true,
  meta JSONB,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
CREATE INDEX idx_geofences_area ON geofences USING GIST(area);
```

#### Alert Rules
```sql
CREATE TABLE alert_rules (
  id BIGSERIAL PRIMARY KEY,
  organization_id BIGINT REFERENCES organizations(id),
  name VARCHAR(255),
  type VARCHAR(50), -- speeding, geofence_enter, geofence_exit, idle, fuel_low
  conditions JSONB,
  enabled BOOLEAN DEFAULT true,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

#### Alerts
```sql
CREATE TABLE alerts (
  id BIGSERIAL PRIMARY KEY,
  organization_id BIGINT REFERENCES organizations(id),
  device_id BIGINT REFERENCES devices(id),
  rule_id BIGINT REFERENCES alert_rules(id),
  type VARCHAR(50),
  title VARCHAR(255),
  message TEXT,
  severity VARCHAR(20), -- info, warning, critical
  seen BOOLEAN DEFAULT false,
  payload JSONB,
  created_at TIMESTAMP
);
CREATE INDEX idx_alerts_org_seen ON alerts(organization_id, seen);
```

### Telemetry Tables (TimescaleDB)

#### Positions (Hypertable)
```sql
CREATE TABLE positions (
  time TIMESTAMPTZ NOT NULL,
  device_id BIGINT NOT NULL,
  location GEOMETRY(POINT, 4326),
  latitude DECIMAL(10, 8),
  longitude DECIMAL(11, 8),
  speed DECIMAL(5, 2),
  heading SMALLINT,
  altitude SMALLINT,
  satellites SMALLINT,
  ignition BOOLEAN,
  fuel_level DECIMAL(5, 2),
  battery_voltage DECIMAL(5, 2),
  raw_data JSONB
);

-- Convert to hypertable
SELECT create_hypertable('positions', 'time');

-- Create indexes
CREATE INDEX idx_positions_device_time ON positions (device_id, time DESC);
CREATE INDEX idx_positions_location ON positions USING GIST (location);

-- Set up compression (after 7 days)
ALTER TABLE positions SET (
  timescaledb.compress,
  timescaledb.compress_segmentby = 'device_id'
);

SELECT add_compression_policy('positions', INTERVAL '7 days');

-- Set up retention (keep 180 days)
SELECT add_retention_policy('positions', INTERVAL '180 days');
```

---

## 🔧 Laravel Service Structure

### Models

**app/Models/Device.php**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['organization_id', 'imei', 'name', 'protocol', 'sim_number', 'status', 'settings'];
    protected $casts = ['settings' => 'array'];

    public function organization() {
        return $this->belongsTo(Organization::class);
    }

    public function asset() {
        return $this->hasOne(Asset::class);
    }

    public function lastPosition() {
        return $this->hasOne(Position::class, 'device_id')
            ->latest('time');
    }
}
```

**app/Models/Geofence.php**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class Geofence extends Model
{
    use PostgisTrait;

    protected $fillable = ['organization_id', 'name', 'area', 'color', 'alerts_enabled', 'meta'];
    protected $casts = ['meta' => 'array', 'alerts_enabled' => 'boolean'];
    protected $postgisFields = ['area'];

    public function organization() {
        return $this->belongsTo(Organization::class);
    }

    public function isInside($lat, $lng) {
        return \DB::select("
            SELECT ST_Contains(area, ST_SetSRID(ST_MakePoint(?, ?), 4326)) as inside
            FROM geofences WHERE id = ?
        ", [$lng, $lat, $this->id])[0]->inside;
    }
}
```

### Jobs

**app/Jobs/ProcessPositionJob.php**
```php
<?php

namespace App\Jobs;

use App\Models\Device;
use App\Models\Position;
use App\Events\DevicePositionUpdated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class ProcessPositionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $deviceId,
        public array $positionData
    ) {}

    public function handle()
    {
        // 1. Save to TimescaleDB
        Position::create([
            'time' => $this->positionData['time'],
            'device_id' => $this->deviceId,
            'latitude' => $this->positionData['lat'],
            'longitude' => $this->positionData['lng'],
            'speed' => $this->positionData['speed'] ?? 0,
            'heading' => $this->positionData['heading'] ?? 0,
            'ignition' => $this->positionData['ignition'] ?? false,
            'fuel_level' => $this->positionData['fuel_level'] ?? null,
            'raw_data' => $this->positionData,
        ]);

        // 2. Update Redis last known state
        Redis::hset("device:{$this->deviceId}:last", [
            'lat' => $this->positionData['lat'],
            'lng' => $this->positionData['lng'],
            'speed' => $this->positionData['speed'] ?? 0,
            'time' => $this->positionData['time'],
        ]);

        // 3. Broadcast to WebSocket
        broadcast(new DevicePositionUpdated($this->deviceId, $this->positionData));

        // 4. Evaluate alert rules
        EvaluateAlertRulesJob::dispatch($this->deviceId, $this->positionData);
    }
}
```

### Events

**app/Events/DevicePositionUpdated.php**
```php
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DevicePositionUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $deviceId,
        public array $position
    ) {}

    public function broadcastOn()
    {
        return new Channel("device.{$this->deviceId}");
    }

    public function broadcastAs()
    {
        return 'DevicePositionUpdated';
    }
}
```

---

## 🔌 API Routes

**routes/api.php**
```php
<?php

use App\Http\Controllers\Api\V1;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/login', [V1\AuthController::class, 'login']);
    Route::post('/register', [V1\AuthController::class, 'register']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [V1\AuthController::class, 'me']);
        Route::post('/logout', [V1\AuthController::class, 'logout']);

        // Devices/Assets
        Route::apiResource('devices', V1\DeviceController::class);
        Route::get('/positions/last', [V1\PositionController::class, 'last']);
        Route::get('/positions', [V1\PositionController::class, 'index']);
        Route::get('/trips', [V1\TripController::class, 'index']);

        // Geofences
        Route::apiResource('geofences', V1\GeofenceController::class);
        Route::get('/geofences/check', [V1\GeofenceController::class, 'check']);

        // Alerts
        Route::apiResource('alerts', V1\AlertController::class);
        Route::patch('/alerts/{alert}/read', [V1\AlertController::class, 'markAsRead']);
        Route::post('/alerts/read-all', [V1\AlertController::class, 'markAllAsRead']);

        // Alert Rules
        Route::apiResource('alert-rules', V1\AlertRuleController::class);

        // Reports
        Route::post('/reports', [V1\ReportController::class, 'generate']);
        Route::get('/reports', [V1\ReportController::class, 'index']);
        Route::get('/reports/{report}', [V1\ReportController::class, 'show']);
        Route::get('/reports/{report}/download', [V1\ReportController::class, 'download']);

        // Telemetry
        Route::get('/telemetry/current/{device}', [V1\TelemetryController::class, 'current']);
        Route::get('/telemetry/history/{device}', [V1\TelemetryController::class, 'history']);
        Route::get('/telemetry/diagnostics/{device}', [V1\TelemetryController::class, 'diagnostics']);

        // Statistics
        Route::get('/statistics/dashboard', [V1\StatisticsController::class, 'dashboard']);
        Route::get('/statistics/fleet-status', [V1\StatisticsController::class, 'fleetStatus']);
        Route::get('/statistics/activity', [V1\StatisticsController::class, 'activity']);

        // Organizations
        Route::get('/organizations/current', [V1\OrganizationController::class, 'current']);
        Route::put('/organizations/current', [V1\OrganizationController::class, 'update']);
        Route::get('/organizations/users', [V1\OrganizationController::class, 'users']);

        // Webhooks
        Route::apiResource('webhooks', V1\WebhookController::class);
        Route::post('/webhooks/{webhook}/test', [V1\WebhookController::class, 'test']);
    });
});
```

---

## 🐳 Docker Compose Setup

See `docker-compose.yml` in the backend directory.

---

## 📝 Next Steps

1. **Set up Laravel backend** following the composer commands above
2. **Configure databases** (PostgreSQL + TimescaleDB)
3. **Choose ingestion method**:
   - **Easy**: Use Traccar
   - **Custom**: Build Go/Kotlin service
4. **Install message bus** (Kafka or NATS)
5. **Configure WebSockets** in Laravel
6. **Test API endpoints** with Postman
7. **Connect React frontend** to APIs
8. **Deploy** using Docker Compose

---

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Orchid Platform](https://orchid.software/)
- [PostGIS](https://postgis.net/)
- [TimescaleDB](https://www.timescale.com/)
- [Traccar](https://www.traccar.org/)
- [Laravel WebSockets](https://beyondco.de/docs/laravel-websockets)

---

**Need Help?** Check the integration examples in `src/services/api.js` and `src/services/websocket.js`

