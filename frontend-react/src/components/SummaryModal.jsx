import React from 'react';

const SummaryModal = ({ isOpen, onClose, analysis }) => {
  if (!isOpen || !analysis) return null;

  return (
    <div className="text-start  fixed inset-0 bg-white/30 backdrop-blur-sm flex items-center justify-center p-4 z-50">
      <div className="bg-white rounded-lg shadow-2xl w-full max-w-lg overflow-hidden border border-gray-200">
        <div className="bg-indigo-600 px-4 py-3 flex justify-between items-center">
          <h3 className="text-white font-bold">Smart AI Analysis</h3>
          <button onClick={onClose} className="text-white hover:text-gray-200 text-xl">✕</button>
        </div>
        <div className="p-6 space-y-4">
          <div>
            <h4 className="text-xs font-bold text-gray-400 uppercase tracking-widest">Summary</h4>
            <p className="mt-1 text-gray-900 leading-relaxed">{analysis.summary}</p>
          </div>
          <div>
            <h4 className="text-xs font-bold text-gray-400 uppercase tracking-widest">Suggested Next Steps</h4>
            <ul className="mt-2 space-y-2">
              {Array.isArray(analysis.suggested_next_actions) ? (
                analysis.suggested_next_actions.map((action, index) => (
                  <li key={index} className="flex items-start gap-2 text-sm text-gray-700">
                    <span className="text-indigo-500 font-bold">•</span>
                    {action}
                  </li>
                ))
              ) : (
                <li className="text-sm text-gray-700">{analysis.suggested_next_actions}</li>
              )}
            </ul>
          </div>
        </div>
        <div className="bg-gray-50 px-4 py-3 text-right border-t">
          <button onClick={onClose} className="px-5 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
            Close
          </button>
        </div>
      </div>
    </div>
  );
};

export default SummaryModal;