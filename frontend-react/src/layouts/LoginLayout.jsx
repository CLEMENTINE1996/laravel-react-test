import React from 'react';

const LoginLayout = ({ children }) => {
    return (
        <div className="min-h-screen flex flex-col items-center justify-center bg-gray-100">
            <div className="mb-8 text-center">
                <h1 className="text-4xl font-extrabold text-gray-900 tracking-tight">
                    Ticket Management System
                </h1>
                <p className="mt-2 text-gray-600">Enter your credentials to access the dashboard</p>
            </div>

            <div className="bg-white p-8 rounded-xl shadow-lg w-full max-w-md border border-gray-200">
                <h2 className="text-2xl font-bold text-center text-gray-800">Login to Your Account</h2>
                {children}
            </div>
            
            <footer className="mt-8 text-gray-400 text-sm">
                &copy; {new Date().getFullYear()} Support Portal
            </footer>
        </div>
    );
}

export default LoginLayout;