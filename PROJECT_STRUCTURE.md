# GPS Track - Project Structure

## 📦 Repositories

The GPS tracking system is now separated into two independent repositories:

### 1. Frontend Repository (Current)
**Repository**: https://github.com/ridaFD/gps-track

**Technology**: React 19.2.0

**Features**:
- ✅ Dashboard with real-time map
- ✅ Asset management
- ✅ Geofencing & POI
- ✅ Alerts & notifications
- ✅ Reports & analytics
- ✅ Advanced telemetry
- ✅ API integration layer
- ✅ WebSocket support

**Structure**:
```
gps-track/
├── src/
│   ├── components/       # Reusable components (Header, Sidebar)
│   ├── pages/           # Main pages (Dashboard, Assets, etc.)
│   ├── services/        # API & WebSocket services
│   ├── App.js           # Main app component
│   └── index.js         # Entry point
├── public/              # Static assets
├── INTEGRATION.md       # Backend integration guide
├── QUICKSTART.md        # Quick start guide
├── FEATURES.md          # Detailed features
├── DEPLOYMENT.md        # Deployment instructions
└── README.md            # Main documentation
```

### 2. Backend Repository (New)
**Repository**: https://github.com/ridaFD/gps-track-backend

**Technology**: Laravel 11 + PostgreSQL + TimescaleDB

**Features**:
- ✅ RESTful API
- ✅ Multi-tenancy
- ✅ Real-time WebSockets
- ✅ GPS data ingestion
- ✅ Geospatial queries (PostGIS)
- ✅ Time-series telemetry (TimescaleDB)
- ✅ Alert rules engine
- ✅ Report generation
- ✅ Admin panel (Orchid)
- ✅ RBAC (Spatie Permissions)

**Structure**:
```
gps-track-backend/
├── app/
│   ├── Models/          # Eloquent models
│   ├── Http/
│   │   └── Controllers/ # API controllers
│   ├── Jobs/            # Queue jobs
│   └── Events/          # WebSocket events
├── database/
│   └── migrations/      # Database schema
├── routes/
│   └── api.php          # API routes
├── docker-compose.yml   # Docker setup
├── Dockerfile          # Laravel container
├── SETUP.md            # Setup instructions
└── README.md           # Documentation
```

---

## 🔗 How They Work Together

```
┌─────────────────────────────────────────────────┐
│   React Frontend (gps-track)                    │
│   http://localhost:3000                          │
│   - User Interface                               │
│   - Real-time Map                                │
│   - Charts & Visualizations                      │
└──────────────┬──────────────────────────────────┘
               │
               │ HTTP API + WebSocket
               │
┌──────────────▼──────────────────────────────────┐
│   Laravel Backend (gps-track-backend)           │
│   http://localhost:8000                          │
│   - REST API                                     │
│   - Business Logic                               │
│   - Database Management                          │
│   - Real-time Processing                         │
└──────────────┬──────────────────────────────────┘
               │
               ├─→ PostgreSQL + PostGIS
               ├─→ TimescaleDB
               ├─→ Redis
               ├─→ Kafka
               └─→ Traccar (Optional)
```

---

## 🚀 Quick Start

### 1. Clone Both Repositories

```bash
# Clone frontend
git clone https://github.com/ridaFD/gps-track.git
cd gps-track
npm install

# Clone backend (in a separate directory)
cd ..
git clone https://github.com/ridaFD/gps-track-backend.git
cd gps-track-backend
```

### 2. Start Backend First

```bash
# Using Docker Compose (recommended)
cd gps-track-backend
docker-compose up -d

# Or manually
composer install
php artisan migrate
php artisan serve
php artisan websockets:serve
php artisan horizon
```

### 3. Configure Frontend

```bash
cd gps-track

# Create environment file
cp .env.local.example .env.local

# Edit .env.local
REACT_APP_API_URL=http://localhost:8000/api/v1
REACT_APP_WS_HOST=localhost
REACT_APP_WS_PORT=6001
```

### 4. Start Frontend

```bash
npm start
```

Visit: http://localhost:3000

---

## 📝 Development Workflow

### Working on Frontend Only
```bash
cd gps-track
npm start
# App runs with mock data
```

### Working with Backend Integration
```bash
# Terminal 1: Backend
cd gps-track-backend
docker-compose up

# Terminal 2: Frontend
cd gps-track
npm start
```

### Making Changes

**Frontend Changes**:
```bash
cd gps-track
# Make changes
git add .
git commit -m "Your message"
git push origin main
```

**Backend Changes**:
```bash
cd gps-track-backend
# Make changes
git add .
git commit -m "Your message"
git push origin main
```

---

## 🔑 Key Integration Points

### API Services (Frontend)
Located in `src/services/api.js`:
- `authAPI` - Authentication
- `assetsAPI` - Device/asset management
- `geofencesAPI` - Geofence management
- `alertsAPI` - Alert management
- `reportsAPI` - Report generation
- `telemetryAPI` - Real-time telemetry
- `statisticsAPI` - Dashboard stats

### WebSocket Service (Frontend)
Located in `src/services/websocket.js`:
- `subscribeToDevicePosition()` - Real-time positions
- `subscribeToAlerts()` - New alerts
- `subscribeToTelemetry()` - Live telemetry

### API Endpoints (Backend)
See backend `routes/api.php`:
- `GET /api/v1/devices` - List devices
- `POST /api/v1/geofences` - Create geofence
- `GET /api/v1/positions/last` - Last position
- `GET /api/v1/alerts` - List alerts
- And many more...

---

## 📚 Documentation

### Frontend Documentation
- [README.md](./README.md) - Overview & features
- [QUICKSTART.md](./QUICKSTART.md) - Getting started
- [FEATURES.md](./FEATURES.md) - Detailed features
- [INTEGRATION.md](./INTEGRATION.md) - Backend integration
- [DEPLOYMENT.md](./DEPLOYMENT.md) - Production deployment

### Backend Documentation
- [Backend README](https://github.com/ridaFD/gps-track-backend/blob/main/README.md) - Overview
- [SETUP.md](https://github.com/ridaFD/gps-track-backend/blob/main/SETUP.md) - Installation guide
- API.md - API documentation (coming soon)

---

## 🎯 Benefits of Separation

### ✅ **Independent Development**
- Frontend and backend teams can work independently
- Different release cycles
- Easier to maintain

### ✅ **Technology Flexibility**
- Can swap frontend (e.g., Vue, Angular) without touching backend
- Can swap backend (e.g., Node.js, Go) without touching frontend

### ✅ **Scalability**
- Scale frontend and backend independently
- Deploy frontend to CDN
- Deploy backend to multiple regions

### ✅ **Deployment Options**
- Frontend: Vercel, Netlify, Cloudflare Pages, S3+CloudFront
- Backend: AWS, DigitalOcean, Heroku, Docker, Kubernetes

### ✅ **Team Collaboration**
- Different repos = different access controls
- Separate CI/CD pipelines
- Independent versioning

---

## 🆘 Troubleshooting

### Backend Not Connecting
1. Check backend is running: `http://localhost:8000/api/v1/devices`
2. Verify `.env.local` has correct API URL
3. Check CORS settings in backend

### WebSocket Not Working
1. Ensure WebSocket server is running: `php artisan websockets:serve`
2. Check port 6001 is open
3. Verify WebSocket key matches in both repos

### Changes Not Reflecting
1. Clear browser cache
2. Restart dev server: `npm start`
3. Check you're in the right repository

---

## 💡 Next Steps

1. ✅ **Set up backend** - Follow backend SETUP.md
2. ✅ **Configure integration** - Update .env.local
3. ⬜ **Test API endpoints** - Use Postman/Insomnia
4. ⬜ **Replace mock data** - Connect pages to real API
5. ⬜ **Add authentication** - Implement login/logout
6. ⬜ **Deploy to production** - Follow deployment guides

---

**Need Help?** 
- Frontend issues: [gps-track/issues](https://github.com/ridaFD/gps-track/issues)
- Backend issues: [gps-track-backend/issues](https://github.com/ridaFD/gps-track-backend/issues)

---

**Happy Coding!** 🚀

