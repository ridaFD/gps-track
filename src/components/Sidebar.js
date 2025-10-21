import React from 'react';
import { NavLink } from 'react-router-dom';
import { 
  LayoutDashboard, 
  MapPin, 
  Zap, 
  Bell, 
  FileText, 
  Activity,
  Navigation
} from 'lucide-react';
import './Sidebar.css';

const Sidebar = ({ isOpen }) => {
  const menuItems = [
    { path: '/dashboard', icon: LayoutDashboard, label: 'Dashboard' },
    { path: '/assets', icon: MapPin, label: 'Assets' },
    { path: '/geofencing', icon: Navigation, label: 'Geofencing' },
    { path: '/alerts', icon: Bell, label: 'Alerts' },
    { path: '/reports', icon: FileText, label: 'Reports' },
    { path: '/telemetry', icon: Activity, label: 'Telemetry' }
  ];

  return (
    <div className={`sidebar ${isOpen ? 'open' : 'closed'}`}>
      <div className="sidebar-header">
        <Zap className="logo-icon" size={32} />
        {isOpen && <span className="logo-text">GPS Track</span>}
      </div>
      
      <nav className="sidebar-nav">
        {menuItems.map((item) => (
          <NavLink
            key={item.path}
            to={item.path}
            className={({ isActive }) => 
              `nav-item ${isActive ? 'active' : ''}`
            }
            title={!isOpen ? item.label : ''}
          >
            <item.icon className="nav-icon" size={20} />
            {isOpen && <span className="nav-label">{item.label}</span>}
          </NavLink>
        ))}
      </nav>
    </div>
  );
};

export default Sidebar;

