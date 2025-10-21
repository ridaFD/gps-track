// Authentication Helper Service
import { authAPI } from './api';

// Token management
export const setAuthToken = (token) => {
  localStorage.setItem('auth_token', token);
};

export const getAuthToken = () => {
  return localStorage.getItem('auth_token');
};

export const removeAuthToken = () => {
  localStorage.removeItem('auth_token');
};

export const isAuthenticated = () => {
  return !!getAuthToken();
};

// User management
export const setUser = (user) => {
  localStorage.setItem('user', JSON.stringify(user));
};

export const getUser = () => {
  const user = localStorage.getItem('user');
  return user ? JSON.parse(user) : null;
};

export const removeUser = () => {
  localStorage.removeItem('user');
};

// Login function
export const login = async (email, password) => {
  try {
    const response = await authAPI.login(email, password);
    
    if (response.token) {
      setAuthToken(response.token);
      setUser(response.user);
      return { success: true, user: response.user };
    }
    
    return { success: false, message: 'No token received' };
  } catch (error) {
    return { 
      success: false, 
      message: error.response?.data?.message || 'Login failed' 
    };
  }
};

// Register function
export const register = async (name, email, password, password_confirmation) => {
  try {
    const response = await authAPI.register({
      name,
      email,
      password,
      password_confirmation,
    });
    
    if (response.token) {
      setAuthToken(response.token);
      setUser(response.user);
      return { success: true, user: response.user };
    }
    
    return { success: false, message: 'No token received' };
  } catch (error) {
    return { 
      success: false, 
      message: error.response?.data?.message || 'Registration failed',
      errors: error.response?.data?.errors
    };
  }
};

// Logout function
export const logout = async () => {
  try {
    await authAPI.logout();
  } catch (error) {
    console.error('Logout API error:', error);
  } finally {
    removeAuthToken();
    removeUser();
    window.location.href = '/login';
  }
};

export default {
  login,
  register,
  logout,
  isAuthenticated,
  getUser,
  getAuthToken,
};

