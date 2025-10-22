import React, { useState } from 'react';
import { Menu, Search, Bell, User, LogOut } from 'lucide-react';
import { getUser, logout } from '../services/auth';
import OrganizationSwitcher from './OrganizationSwitcher';
import './Header.css';

const Header = ({ toggleSidebar }) => {
  const user = getUser();
  const [showUserMenu, setShowUserMenu] = useState(false);

  const handleLogout = () => {
    if (window.confirm('Are you sure you want to logout?')) {
      logout();
    }
  };

  return (
    <header className="header">
      <div className="header-left">
        <button className="menu-btn" onClick={toggleSidebar}>
          <Menu size={20} />
        </button>
        <div className="search-container">
          <Search className="search-icon" size={18} />
          <input 
            type="text" 
            placeholder="Search assets, locations..." 
            className="search-input"
          />
        </div>
      </div>
      
      <div className="header-right">
        <OrganizationSwitcher />
        <button className="header-icon-btn">
          <Bell size={20} />
          <span className="notification-badge">3</span>
        </button>
        <div className="user-profile-container">
          <div 
            className="user-profile" 
            onClick={() => setShowUserMenu(!showUserMenu)}
          >
            <div className="user-avatar">
              <User size={18} />
            </div>
            <span className="user-name">{user?.name || 'User'}</span>
          </div>
          {showUserMenu && (
            <div className="user-menu">
              <div className="user-menu-header">
                <p className="user-menu-name">{user?.name}</p>
                <p className="user-menu-email">{user?.email}</p>
              </div>
              <button className="user-menu-item" onClick={handleLogout}>
                <LogOut size={16} />
                <span>Logout</span>
              </button>
            </div>
          )}
        </div>
      </div>
    </header>
  );
};

export default Header;

