import React from 'react';
import { Menu, Search, Bell, User } from 'lucide-react';
import './Header.css';

const Header = ({ toggleSidebar }) => {
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
        <button className="header-icon-btn">
          <Bell size={20} />
          <span className="notification-badge">3</span>
        </button>
        <div className="user-profile">
          <div className="user-avatar">
            <User size={18} />
          </div>
          <span className="user-name">Admin User</span>
        </div>
      </div>
    </header>
  );
};

export default Header;

