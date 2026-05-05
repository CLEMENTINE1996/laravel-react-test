import React, { useEffect, useState } from 'react';
import StorageService from '../services/storageService';

const TicketModal = ({ isOpen, onClose, onSave, ticket = null }) => {
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    priority: 'low',
    category: 'bug',
    status: 'open',
    requestor_id: StorageService.getUserId()
  });

  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (isOpen) {
      setLoading(false);
      if (ticket) {
        setFormData(ticket);
      } else {
        setFormData({ 
          title: '', 
          description: '', 
          priority: 'low', 
          category: 'bug', 
          status: 'open', 
          requestor_id: StorageService.getUserId() 
        });
      }
    }
  }, [ticket, isOpen]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);

    try {
      await onSave(formData);
      alert("Ticket saved successfully!");
      onClose();

    } catch (err) {
      const errorMessage = err.response?.data?.message || err.message || "Unable to save the ticket.";
      alert(`Error: ${errorMessage}`);
    } finally {
      setLoading(false);
    }
  };

  if (!isOpen) return null;

  return (
    <div className="text-start fixed inset-0 bg-white/30 backdrop-blur-sm flex items-center justify-center p-4 z-50">
      <div className="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md border border-gray-100 relative overflow-hidden">
        
        {loading && (
          <div className="absolute inset-0 bg-white/60 backdrop-blur-[2px] z-10 flex flex-col items-center justify-center">
            <div className="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
            <p className="mt-2 text-sm font-medium text-blue-600">Processing...</p>
          </div>
        )}

        <h2 className="text-xl font-bold mb-4 text-gray-800">{ticket ? 'Edit Ticket' : 'Create New Ticket'}</h2>
        
        <form onSubmit={handleSubmit}>
          <div className="space-y-4">
            <input 
              className="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none disabled:bg-gray-50" 
              placeholder="Title" 
              value={formData.title}
              onChange={e => setFormData({...formData, title: e.target.value})} 
              required 
              disabled={loading}
            />

            <select 
              className="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none disabled:bg-gray-50"
              value={formData.category}
              onChange={e => setFormData({...formData, category: e.target.value})}
              required
              disabled={loading}
            >
              <option value="bug">Bug</option>
              <option value="feature_request">Feature Request</option>
              <option value="documentation">Documentation</option>
              <option value="complain">Complain</option>
              <option value="suggestion">Suggestion</option>
            </select>

            <div className="flex gap-2">
              <select 
                className="w-1/2 border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none disabled:bg-gray-50"
                value={formData.status}
                onChange={e => setFormData({...formData, status: e.target.value})}
                required
                disabled={loading}
              >
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="closed">Closed</option>
              </select>

              <select 
                className="w-1/2 border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none disabled:bg-gray-50"
                value={formData.priority}
                onChange={e => setFormData({...formData, priority: e.target.value})}
                required
                disabled={loading}
              >
                <option value="low">Low Priority</option>
                <option value="medium">Medium Priority</option>
                <option value="high">High Priority</option>
              </select>
            </div>

            <textarea 
              className="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none min-h-[100px] disabled:bg-gray-50" 
              placeholder="Description" 
              value={formData.description}
              onChange={e => setFormData({...formData, description: e.target.value})} 
              required 
              disabled={loading}
            />
          </div>

          <div className="flex justify-end gap-3 mt-8">
            <button 
              type="button" 
              onClick={onClose} 
              className="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors disabled:opacity-50"
              disabled={loading}
            >
              Cancel
            </button>
            <button 
              type="submit" 
              className="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all disabled:bg-blue-400"
              disabled={loading}
            >
              {loading ? 'Saving...' : (ticket ? 'Update Ticket' : 'Save Ticket')}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default TicketModal;