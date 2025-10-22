import React, { useState, useEffect } from 'react';
import { organizationsAPI } from '../services/api';
import { Building2, ChevronDown, Check } from 'lucide-react';
import './OrganizationSwitcher.css';

const OrganizationSwitcher = () => {
  const [organizations, setOrganizations] = useState([]);
  const [currentOrgId, setCurrentOrgId] = useState(null);
  const [isOpen, setIsOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [switching, setSwitching] = useState(false);

  useEffect(() => {
    fetchOrganizations();
  }, []);

  const fetchOrganizations = async () => {
    try {
      setLoading(true);
      const response = await organizationsAPI.list();
      setOrganizations(response.data);
      setCurrentOrgId(response.current_organization_id);
    } catch (error) {
      console.error('Error fetching organizations:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleSwitch = async (orgId) => {
    if (orgId === currentOrgId || switching) return;

    try {
      setSwitching(true);
      await organizationsAPI.switch(orgId);
      setCurrentOrgId(orgId);
      setIsOpen(false);
      
      // Reload the page to fetch new organization's data
      window.location.reload();
    } catch (error) {
      console.error('Error switching organization:', error);
      alert('Failed to switch organization. Please try again.');
    } finally {
      setSwitching(false);
    }
  };

  const currentOrg = organizations.find(org => org.id === currentOrgId);

  if (loading) {
    return (
      <div className="org-switcher-loading">
        <Building2 size={18} />
        <span>Loading...</span>
      </div>
    );
  }

  if (organizations.length === 0) {
    return null;
  }

  return (
    <div className="org-switcher">
      <button
        className="org-switcher-button"
        onClick={() => setIsOpen(!isOpen)}
        disabled={switching}
      >
        <Building2 size={18} />
        <div className="org-switcher-text">
          <span className="org-name">{currentOrg?.name || 'Select Organization'}</span>
          <span className="org-plan">{currentOrg?.plan || ''}</span>
        </div>
        <ChevronDown size={16} className={`chevron ${isOpen ? 'open' : ''}`} />
      </button>

      {isOpen && (
        <>
          <div className="org-switcher-overlay" onClick={() => setIsOpen(false)} />
          <div className="org-switcher-dropdown">
            <div className="org-switcher-header">
              <Building2 size={16} />
              <span>Switch Organization</span>
            </div>
            <div className="org-switcher-list">
              {organizations.map((org) => (
                <button
                  key={org.id}
                  className={`org-switcher-item ${org.id === currentOrgId ? 'active' : ''}`}
                  onClick={() => handleSwitch(org.id)}
                  disabled={switching}
                >
                  <div className="org-item-content">
                    <div className="org-item-name">{org.name}</div>
                    <div className="org-item-details">
                      <span className="org-item-plan">{org.plan}</span>
                      <span className="org-item-role">{org.role}</span>
                      {org.is_on_trial && <span className="org-item-trial">Trial</span>}
                    </div>
                    <div className="org-item-stats">
                      {org.device_count}/{org.max_devices} devices • 
                      {org.user_count}/{org.max_users} users
                    </div>
                  </div>
                  {org.id === currentOrgId && (
                    <Check size={18} className="org-item-check" />
                  )}
                </button>
              ))}
            </div>
            {switching && (
              <div className="org-switcher-loading-overlay">
                <div className="spinner"></div>
                <span>Switching...</span>
              </div>
            )}
          </div>
        </>
      )}
    </div>
  );
};

export default OrganizationSwitcher;

