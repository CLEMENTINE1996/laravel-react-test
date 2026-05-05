import React from 'react';

const Alert = ({ message, type = 'error' }) => {
  const bgClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';

  return (
    <div className={`${bgClass} border px-4 py-3 rounded relative mb-4`} role="alert">
      <span className="block sm:inline text-sm">{message}</span>
    </div>
  );
};

export default Alert;