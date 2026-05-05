const TOKEN_KEY = 'ACCESS_TOKEN';
const USER_KEY = 'USER_DATA';

const StorageService = {
  // Save both token and user data
  saveAuthData: (token, user) => {
    localStorage.setItem(TOKEN_KEY, token);
    localStorage.setItem(USER_KEY, JSON.stringify(user));
  },

  // Retrieve the Bearer token
  getToken: () => {
    return localStorage.getItem(TOKEN_KEY);
  },

  // Retrieve the User object
  getUser: () => {
    const user = localStorage.getItem(USER_KEY);
    return user ? JSON.parse(user) : null;
  },

  // Helper to get user ID directly
  getUserId: () => {
    const user = StorageService.getUser();
    return user ? user.id : null;
  },

  // Clear everything on logout
  clearAuthData: () => {
    localStorage.removeItem(TOKEN_KEY);
    localStorage.removeItem(USER_KEY);
  }
};

export default StorageService;