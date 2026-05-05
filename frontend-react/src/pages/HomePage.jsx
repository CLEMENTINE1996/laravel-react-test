import React, { useState, useEffect, useMemo } from 'react';
import HomeLayout from '../layouts/HomeLayout';
import ticketApi from '../api/ticket-api';
import TicketModal from '../components/TicketModal';
import SummaryModal from '../components/SummaryModal'; 
import Button from '../components/Elements/Button';

const HomePage = () => {
  const [tickets, setTickets] = useState([]);
  const [loading, setLoading] = useState(true); 
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [isSummaryOpen, setIsSummaryOpen] = useState(false); 
  const [editingTicket, setEditingTicket] = useState(null);
  const [selectedAnalysis, setSelectedAnalysis] = useState(null); 
  
  const [searchTerm, setSearchTerm] = useState('');
  const [activeSearch, setActiveSearch] = useState('');
  
  const [filterStatus, setFilterStatus] = useState('all');
  const [filterPriority, setFilterPriority] = useState('all');

  useEffect(() => {
    fetchTickets();
  }, [ activeSearch, filterStatus, filterPriority ]);

  const fetchTickets = async () => {
    setLoading(true);
    try {
      const params = {
        search: activeSearch || null,
        status: filterStatus === 'all' ? null : filterStatus,
        priority: filterPriority === 'all' ? null : filterPriority,
      };

      const { data } = await ticketApi.getTickets(params);
      setTickets(data);
    } catch (err) {
      console.error("Failed to fetch tickets", err);
    } finally {
      setLoading(false); 
    }
  };

  const handleSave = async (formData) => {
    try {
      if (editingTicket) {
        await ticketApi.updateTicket(editingTicket.id, formData);
      } else {
        await ticketApi.createTicket(formData);
      }
      setIsModalOpen(false);
      fetchTickets();
    } catch (err) {
      alert("Error saving ticket.");
    }
  };

  const handleDelete = async (id) => {
    if (window.confirm("Are you sure you want to delete this ticket?")) {
      try {
        await ticketApi.deleteTicket(id);
        alert("Ticket deleted successfully.");
        fetchTickets();
      } catch (err) {
        console.error("Failed to delete ticket", err);
        alert("Error deleting ticket.");
      }
    }
  };

  const handleSearchTrigger = () => {
    setActiveSearch(searchTerm);
  };

  const handleKeyDown = (e) => {
    if (e.key === 'Enter') {
      handleSearchTrigger();
    }
  };

  return (
    <HomeLayout>
      <div className="flex justify-between items-center mb-6">
        <h4 className="text-2xl font-bold text-gray-800">Support Tickets</h4>
        <Button
          onClick={() => { setEditingTicket(null); setIsModalOpen(true); }}
          className="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700 transition"
        >
          Create New Ticket
        </Button>
      </div>

      <div className="bg-white p-4 rounded-lg shadow mb-6 flex flex-wrap gap-4 items-center">
        <div className="flex flex-grow gap-2">
          <input 
            type="text" 
            placeholder="Search by title or description..." 
            className="border rounded px-3 py-2 flex-grow focus:ring-2 focus:ring-indigo-500 outline-none"
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            onKeyDown={handleKeyDown}
          />
          <Button 
            onClick={handleSearchTrigger}
            className="bg-gray-800 text-white px-6 py-2 rounded hover:bg-gray-900 transition"
          >
            Search
          </Button>
        </div>

        <div className="flex gap-4">
          <select 
            className="border rounded px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none" 
            value={filterStatus} 
            onChange={(e) => setFilterStatus(e.target.value)}
          >
            <option value="all">All Statuses</option>
            <option value="open">Open</option>
            <option value="in_progress">In Progress</option>
            <option value="closed">Closed</option>
          </select>
          <select 
            className="border rounded px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none" 
            value={filterPriority} 
            onChange={(e) => setFilterPriority(e.target.value)}
          >
            <option value="all">All Priorities</option>
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
          </select>
        </div>
      </div>

      <div className="bg-white shadow overflow-hidden sm:rounded-lg relative min-h-[200px]">
        {loading && (
          <div className="absolute inset-0 bg-white bg-opacity-70 z-10 flex items-center justify-center">
            <div className="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-600"></div>
          </div>
        )}

        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issue Details</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority/Status</th>
              <th className="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">AI Insights</th>
              <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {tickets.map((ticket) => (
              <tr key={ticket.id} className="hover:bg-gray-50 transition">
                <td className="px-6 py-4">
                  <div className="text-start text-sm font-bold text-gray-900">{ticket.title}</div>
                  <div className="text-xs text-gray-500 truncate max-w-xs">{ticket.description}</div>
                </td>
                <td className="px-6 py-4 whitespace-nowrap">
                  <div className="flex flex-col gap-1">
                    <span className="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 w-fit">{ticket.status}</span>
                    <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full w-fit ${ticket.priority === 'high' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'}`}>
                      {ticket.priority} {ticket.is_escalated && '(Escalated)'}
                    </span>
                  </div>
                </td>
                <td className="px-6 py-4 text-center">
                  {ticket.analysis ? (
                    <button 
                      onClick={() => { setSelectedAnalysis(ticket.analysis); setIsSummaryOpen(true); }}
                      className="text-xs bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full border border-indigo-200 hover:bg-indigo-100 transition"
                    >
                      View Smart Summary
                    </button>
                  ) : (
                    <span className="text-xs text-gray-400 italic">No analysis</span>
                  )}
                </td>
                <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <button onClick={() => { setEditingTicket(ticket); setIsModalOpen(true); }} className="text-indigo-600 hover:text-indigo-900 mr-4">Edit</button>
                  <button onClick={() => handleDelete(ticket.id)} className="text-red-600 hover:text-red-900">Delete</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
        
        {!loading && tickets.length === 0 && (
          <div className="text-center py-10 text-gray-500">No tickets found.</div>
        )}
      </div>

      <TicketModal isOpen={isModalOpen} onClose={() => setIsModalOpen(false)} onSave={handleSave} ticket={editingTicket} />
      
      <SummaryModal 
        isOpen={isSummaryOpen} 
        onClose={() => setIsSummaryOpen(false)} 
        analysis={selectedAnalysis} 
      />
    </HomeLayout>
  );
};

export default HomePage;