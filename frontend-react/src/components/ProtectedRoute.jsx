import { Navigate, Outlet } from 'react-router-dom';
import storageService from '../services/storageService';

const ProtectedRoute = () => {
    const token = storageService.getToken();

    // If no token exists, redirect to the login page
    if (!token) {
        return <Navigate to="/login" replace />;
    }

    return <Outlet />;
};

export default ProtectedRoute;