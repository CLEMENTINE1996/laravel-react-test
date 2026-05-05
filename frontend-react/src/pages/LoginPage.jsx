import React, { useState } from 'react'; 
import authenticationApi from '../api/authentication-api';
import LoginLayout from '../layouts/LoginLayout';
import Button from '../components/Elements/Button';
import StorageService from '../services/storageService';
import LoadingSpinner from '../components/Elements/LoadingSpinner';
import Alert from '../components/Elements/Alert';

const LoginPage = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [message, setMessage] = useState({ text: '', type: '' });

  const handleLoginSubmit = (event) => {
    event.preventDefault(); 
    setIsLoading(true);
    setMessage({ text: '', type: '' }); 

    const credentials = { email, password };

    authenticationApi.login(credentials)
      .then(response => {
        const plainToken = response.data.token.access_token; 
        const userData = response.data.user;

        StorageService.saveAuthData(plainToken, userData);
        setMessage({ text: 'Login successful! Redirecting...', type: 'success' });
        
        setTimeout(() => {
          redirectToHomePage();
        }, 1500);
      })
      .catch(error => {
        setIsLoading(false);
        const errorMsg = error.response?.data?.message || 'Login failed. Please check your credentials.';
        setMessage({ text: errorMsg, type: 'error' });
        console.error('Login failed:', errorMsg);
      });
  };

  const redirectToHomePage = () => {
    window.location.href = '/'; 
  }

  return (
    <LoginLayout>
      <form onSubmit={handleLoginSubmit} className="space-y-4">
        
        {message.text && <Alert message={message.text} type={message.type} />}

        <div>
          <label htmlFor="email" className="text-start block text-sm font-medium text-gray-700 mb-1">
            Email Address
          </label>
          <input
            id="email"
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
            disabled={isLoading}
            className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100"
            placeholder="you@example.com"
          />
        </div>

        <div>
          <label htmlFor="password" className="text-start  block text-sm font-medium text-gray-700 mb-1">
            Password
          </label>
          <input
            id="password"
            type="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
            disabled={isLoading}
            className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100"
            placeholder="Your password"
          />
        </div>

        <div className="pt-2">
            <Button 
              type="submit" 
              variant="primary" 
              className="w-full flex justify-center items-center"
              disabled={isLoading}
            >
              {isLoading ? <LoadingSpinner /> : 'Sign In'}
            </Button> 
        </div>

      </form>
    </LoginLayout>
  );
};

export default LoginPage;