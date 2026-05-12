import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';

export default function CreateRequest() {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    patientName: '',
    bloodType: 'A+',
    hospitalName: '',
    unitsRequired: 1,
    urgencyLevel: 'NORMAL',
    contactNumber: '',
    city: ''
  });

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const token = localStorage.getItem('bloodlink_token');
      await axios.post(`${import.meta.env.VITE_API_URL}/requests`, formData, {
        headers: token ? { Authorization: `Bearer ${token}` } : {}
      });
      navigate('/dashboard');
    } catch (error) {
      console.error('Error creating request:', error);
      alert('Failed to create request');
    }
  };

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  return (
    <div className="flex-1 flex justify-center py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-2xl w-full bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div className="bg-primary-600 px-6 py-6">
          <h2 className="text-2xl font-bold text-white flex items-center">
            <span className="mr-2">🚨</span> Emergency Blood Request
          </h2>
          <p className="text-primary-100 mt-1">Fill out the details below to notify nearby donors instantly.</p>
        </div>
        
        <form onSubmit={handleSubmit} className="p-6 sm:p-8 space-y-6">
          <div className="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
            
            {/* Patient Name */}
            <div className="sm:col-span-2">
              <label htmlFor="patientName" className="block text-sm font-medium text-slate-700">Patient Name</label>
              <div className="mt-1">
                <input type="text" name="patientName" id="patientName" required value={formData.patientName} onChange={handleChange}
                  className="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border" />
              </div>
            </div>

            {/* Blood Type */}
            <div>
              <label htmlFor="bloodType" className="block text-sm font-medium text-slate-700">Blood Type Needed</label>
              <div className="mt-1">
                <select id="bloodType" name="bloodType" value={formData.bloodType} onChange={handleChange}
                  className="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border bg-white">
                  {['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'].map(type => (
                    <option key={type} value={type}>{type}</option>
                  ))}
                </select>
              </div>
            </div>

            {/* Units */}
            <div>
              <label htmlFor="unitsRequired" className="block text-sm font-medium text-slate-700">Units Required</label>
              <div className="mt-1">
                <input type="number" name="unitsRequired" id="unitsRequired" min="1" max="10" required value={formData.unitsRequired} onChange={handleChange}
                  className="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border" />
              </div>
            </div>

            {/* Hospital Name */}
            <div className="sm:col-span-2">
              <label htmlFor="hospitalName" className="block text-sm font-medium text-slate-700">Hospital Name & City</label>
              <div className="mt-1 flex space-x-2">
                <input type="text" name="hospitalName" placeholder="e.g., Fortis Hospital" required value={formData.hospitalName} onChange={handleChange}
                  className="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-2/3 sm:text-sm border-slate-300 rounded-md py-2 px-3 border" />
                <input type="text" name="city" placeholder="City" required value={formData.city} onChange={handleChange}
                  className="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-1/3 sm:text-sm border-slate-300 rounded-md py-2 px-3 border" />
              </div>
            </div>

            {/* Urgency */}
            <div>
              <label htmlFor="urgencyLevel" className="block text-sm font-medium text-slate-700">Urgency Level</label>
              <div className="mt-1">
                <select id="urgencyLevel" name="urgencyLevel" value={formData.urgencyLevel} onChange={handleChange}
                  className="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border bg-white">
                  <option value="NORMAL">Normal (Within 24-48 hrs)</option>
                  <option value="HIGH">High (Within 12 hrs)</option>
                  <option value="CRITICAL">Critical (Immediate)</option>
                </select>
              </div>
            </div>

            {/* Contact */}
            <div>
              <label htmlFor="contactNumber" className="block text-sm font-medium text-slate-700">Contact Number</label>
              <div className="mt-1">
                <input type="tel" name="contactNumber" id="contactNumber" required value={formData.contactNumber} onChange={handleChange}
                  className="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border" />
              </div>
            </div>

          </div>

          <div className="pt-5 border-t border-slate-200 flex justify-end">
            <button type="button" onClick={() => navigate(-1)}
              className="bg-white py-2 px-4 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
              Cancel
            </button>
            <button type="submit"
              className="ml-3 inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
              Broadcast Request
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
