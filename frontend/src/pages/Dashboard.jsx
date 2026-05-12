import { useState, useEffect } from 'react';
import axios from 'axios';
import Map from '../components/Map';
import { Link } from 'react-router-dom';

export default function Dashboard() {
  const [requests, setRequests] = useState([]);
  const [contactVisible, setContactVisible] = useState({});
  
  useEffect(() => {
    const fetchRequests = async () => {
      try {
        const res = await axios.get(`${import.meta.env.VITE_API_URL}/requests`);
        
        // Map the DB format to our UI format
        const formattedRequests = res.data.map(req => ({
          id: req._id,
          patientName: req.patientName,
          hospital: req.hospitalName,
          bloodType: req.bloodType,
          urgency: req.urgencyLevel,
          units: req.unitsRequired,
          contactNumber: req.contactNumber,
          time: new Date(req.createdAt).toLocaleDateString(),
          position: req.location && req.location.coordinates ? [req.location.coordinates[1], req.location.coordinates[0]] : [30.7333, 76.7794]
        }));
        
        setRequests(formattedRequests);
      } catch (error) {
        console.error('Error fetching requests:', error);
      }
    };
    
    fetchRequests();
  }, []);

  const mapMarkers = requests.map(req => ({
    position: req.position,
    title: req.hospital,
    description: `Patient: ${req.patientName} | Units: ${req.units}`,
    bloodType: req.bloodType
  }));

  return (
    <div className="flex-1 flex flex-col md:flex-row max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 gap-8">
      {/* List Sidebar */}
      <div className="w-full md:w-1/3 flex flex-col space-y-6">
        <div className="flex justify-between items-center">
          <h2 className="text-2xl font-bold text-slate-900">Active Requests</h2>
          <span className="bg-primary-100 text-primary-800 text-xs font-bold px-2 py-1 rounded-full">
            {requests.length} Near You
          </span>
        </div>
        
        <div className="space-y-4 overflow-y-auto pr-2 pb-4 h-[600px] custom-scrollbar">
          {requests.map(req => (
            <div key={req.id} className="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
              {req.urgency === 'CRITICAL' && (
                <div className="absolute top-0 left-0 w-1 h-full bg-primary-600"></div>
              )}
              <div className="flex justify-between items-start">
                <div>
                  <h3 className="font-bold text-lg text-slate-900">{req.hospital}</h3>
                  <p className="text-slate-500 text-sm mt-1">Patient: {req.patientName}</p>
                </div>
                <div className="bg-primary-50 border border-primary-100 text-primary-700 font-bold text-lg px-3 py-1 rounded-lg">
                  {req.bloodType}
                </div>
              </div>
              
              <div className="mt-4 flex items-center justify-between text-sm">
                <span className={`font-semibold ${req.urgency === 'CRITICAL' ? 'text-primary-600' : 'text-orange-500'}`}>
                  {req.urgency} Priority
                </span>
                <span className="text-slate-400">{req.time}</span>
              </div>
              
              {contactVisible[req.id] ? (
                <a href={`tel:${req.contactNumber}`} className="w-full mt-4 flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                  📞 {req.contactNumber || 'No number provided'}
                </a>
              ) : (
                <button onClick={() => setContactVisible(prev => ({ ...prev, [req.id]: true }))} className="w-full mt-4 bg-slate-900 hover:bg-slate-800 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                  ❤️ I Can Donate — Reveal Contact
                </button>
              )}
            </div>
          ))}
          {requests.length === 0 && (
            <div className="text-center py-10 bg-white rounded-xl border border-dashed border-slate-300">
              <span className="text-4xl block mb-2">🙌</span>
              <p className="text-slate-500 font-medium">No active requests nearby.</p>
            </div>
          )}
        </div>
      </div>
      
      {/* Map Area */}
      <div className="w-full md:w-2/3 h-[600px] bg-white p-2 rounded-2xl shadow-sm border border-slate-200">
        <Map markers={mapMarkers} center={[30.7333, 76.7794]} />
      </div>
    </div>
  );
}
