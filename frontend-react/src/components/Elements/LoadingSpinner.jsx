import React from 'react';

const LoadingSpinner = () => (
  <div className="flex justify-center items-center py-2">
    <div className="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
    <span className="ml-2 text-white text-sm">Processing...</span>
  </div>
);

export default LoadingSpinner;