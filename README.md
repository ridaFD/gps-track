# GPS Tracking System

A comprehensive GPS tracking and fleet management application built with React, featuring real-time asset monitoring, geofencing, alerts, analytics, and advanced telemetry.

![React](https://img.shields.io/badge/React-19.2.0-blue)
![License](https://img.shields.io/badge/License-MIT-green)

## 🚀 Features

This application replicates the core features of enterprise GPS tracking platforms like [Navixy](https://www.navixy.com/asset-gps-tracking/features/):

### 📍 Real-Time Tracking
- **Live Asset Monitoring**: Track all your assets on an interactive map in real-time
- **Multi-Asset View**: Monitor multiple vehicles and equipment simultaneously
- **Location History**: View historical movement data and routes
- **Real-time Updates**: Asset positions update automatically every 3 seconds

### 🗺️ Geofencing & POI
- **Create Geofences**: Define circular or polygon geographic boundaries
- **Automatic Alerts**: Receive notifications when assets enter/exit zones
- **Points of Interest**: Mark and track important locations
- **Color-Coded Zones**: Easy visual distinction between different areas
- **Asset Count**: See how many assets are within each geofence

### 🚨 Alerts & Notifications
- **Real-Time Alerts**: Critical, warning, info, and success notifications
- **Alert Types**:
  - Unauthorized movement
  - Low fuel warnings
  - Speeding violations
  - Geofence breaches
  - Extended idle time
  - Route deviations
  - Maintenance reminders
- **Filter & Search**: Easily find and manage alerts
- **Mark as Read**: Track which alerts have been addressed

### 📊 Reports & Analytics
- **Comprehensive Reporting**:
  - Distance & trip analysis
  - Fuel consumption tracking
  - Driver performance metrics
  - Asset utilization statistics
- **Visual Charts**: Interactive charts powered by Recharts
- **Date Range Filters**: Analyze data over custom time periods
- **Export Functionality**: Download reports in various formats
- **Performance Metrics**: Track KPIs and trends over time

### 🔧 Advanced Telemetry
- **Real-Time Diagnostics**:
  - Speed monitoring
  - Engine RPM
  - Temperature readings
  - Battery voltage
  - Fuel levels
  - Odometer readings
- **Historical Data**: View telemetry trends over time
- **Alert System**: Warning and critical alerts for abnormal parameters
- **Diagnostic Information**: Engine status, transmission, brakes, oil pressure, etc.
- **Live Updates**: Telemetry data refreshes every 3 seconds

### 📦 Asset Management
- **Complete Asset Database**: Manage all vehicles and equipment
- **Asset Details**:
  - Name and type
  - IMEI tracking
  - Driver assignment
  - Current status
  - Location information
  - Speed and fuel data
  - Odometer readings
- **CRUD Operations**: Add, edit, and delete assets
- **Search & Filter**: Find assets by name, driver, type, or status
- **Status Tracking**: Active, moving, idle, or inactive states

## 🛠️ Technology Stack

- **Frontend Framework**: React 19.2.0
- **Routing**: React Router DOM
- **Maps**: React-Leaflet with OpenStreetMap
- **Charts**: Recharts
- **Icons**: Lucide React
- **Date Utilities**: date-fns
- **Styling**: Custom CSS with modern design patterns

## 📋 Prerequisites

- Node.js (v14 or higher)
- npm or yarn

## 🔧 Installation

1. **Clone the repository**:
   ```bash
   git clone <your-repo-url>
   cd gps-track
   ```

2. **Install dependencies**:
   ```bash
   npm install
   ```

3. **Start the development server**:
   ```bash
   npm start
   ```

4. **Open your browser**:
   Navigate to [http://localhost:3000](http://localhost:3000)

## 📦 Available Scripts

- `npm start` - Runs the app in development mode
- `npm build` - Builds the app for production
- `npm test` - Launches the test runner
- `npm eject` - Ejects from Create React App (one-way operation)

## 🏗️ Project Structure

```
gps-track/
├── public/
│   ├── index.html
│   └── ...
├── src/
│   ├── components/
│   │   ├── Header.js
│   │   ├── Header.css
│   │   ├── Sidebar.js
│   │   └── Sidebar.css
│   ├── pages/
│   │   ├── Dashboard.js
│   │   ├── Dashboard.css
│   │   ├── Assets.js
│   │   ├── Assets.css
│   │   ├── Geofencing.js
│   │   ├── Geofencing.css
│   │   ├── Alerts.js
│   │   ├── Alerts.css
│   │   ├── Reports.js
│   │   ├── Reports.css
│   │   ├── Telemetry.js
│   │   └── Telemetry.css
│   ├── App.js
│   ├── App.css
│   ├── index.js
│   └── index.css
├── package.json
└── README.md
```

## 🎨 UI/UX Features

- **Modern Design**: Clean and professional interface
- **Responsive Layout**: Works on desktop, tablet, and mobile devices
- **Dark Sidebar**: Elegant navigation with icon-based menu
- **Live Indicators**: Visual feedback for real-time data
- **Smooth Animations**: Polished transitions and interactions
- **Color-Coded Status**: Easy identification of asset states
- **Interactive Charts**: Hover and click for detailed information
- **Search & Filters**: Quick access to specific data
- **Modal Dialogs**: Intuitive forms for data entry

## 🔄 Real-Time Features

The application simulates real-time data updates:
- Asset positions update every 3 seconds
- Telemetry parameters refresh continuously
- Live status indicators show active monitoring
- Fuel levels decrease gradually over time

## 📱 Responsive Design

The application is fully responsive and adapts to:
- Desktop screens (1920px and above)
- Laptops (1366px - 1920px)
- Tablets (768px - 1366px)
- Mobile devices (below 768px)

## 🔐 Future Enhancements

Potential features for future versions:
- User authentication and authorization
- Multi-user support with role-based access
- Integration with real GPS tracking devices
- WebSocket for true real-time updates
- Push notifications
- Mobile app (React Native)
- Advanced route optimization
- Fuel cost calculator
- Maintenance scheduling system
- Integration with external APIs (weather, traffic, etc.)
- Historical playback with route animation
- Custom report builder
- Email/SMS notifications
- Video telematics integration
- Driver behavior scoring

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This project is licensed under the MIT License.

## 🙏 Acknowledgments

- Inspired by [Navixy GPS Tracking Platform](https://www.navixy.com/asset-gps-tracking/features/)
- Maps provided by [OpenStreetMap](https://www.openstreetmap.org/)
- Icons from [Lucide React](https://lucide.dev/)
- Charts powered by [Recharts](https://recharts.org/)

## 📞 Support

For support, please open an issue in the GitHub repository.

---

**Built with ❤️ using React**
