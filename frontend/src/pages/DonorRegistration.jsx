import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';

export default function DonorRegistration() {
  const navigate = useNavigate();
  const [step, setStep] = useState(1);
  const [loading, setLoading] = useState(false);
  const [formData, setFormData] = useState({
    bloodType: 'A+',
    city: '',
    phoneNumber: '',
    otp: ''
  });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSendOTP = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      await axios.post(`${import.meta.env.VITE_API_URL}/auth/send-otp`, {
        phoneNumber: formData.phoneNumber
      });
      setStep(2);
    } catch (error) {
      alert('Failed to send OTP. Please check your number.');
    }
    setLoading(false);
  };

  const handleVerifyOTP = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      await axios.post(`${import.meta.env.VITE_API_URL}/auth/verify-otp`, {
        otp: formData.otp,
        bloodType: formData.bloodType,
        city: formData.city
      });
      navigate('/dashboard');
    } catch (error) {
      alert(error.response?.data?.error || 'Invalid OTP');
    }
    setLoading(false);
  };

  return (
    <div className="flex-1 flex justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50">
      <div className="max-w-md w-full bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div className="bg-primary-600 px-6 py-6 text-center">
          <h2 className="text-2xl font-bold text-white">
            Complete Donor Profile
          </h2>
          <p className="text-primary-100 mt-1">Verify your phone number to receive alerts.</p>
        </div>

        {step === 1 ? (
          <form onSubmit={handleSendOTP} className="p-6 space-y-6">
            <div>
              <label className="block text-sm font-medium text-slate-700">Blood Type</label>
              <select name="bloodType" value={formData.bloodType} onChange={handleChange} className="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 py-2 px-3 border bg-white">
                {['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'].map(type => (
                  <option key={type} value={type}>{type}</option>
                ))}
              </select>
            </div>
            <div>
              <label className="block text-sm font-medium text-slate-700">City</label>
              <input type="text" name="city" required value={formData.city} onChange={handleChange} className="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 py-2 px-3 border" placeholder="e.g. Chandigarh" />
            </div>
            <div>
              <label className="block text-sm font-medium text-slate-700">Phone Number (with country code)</label>
              <input type="tel" name="phoneNumber" required value={formData.phoneNumber} onChange={handleChange} className="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 py-2 px-3 border" placeholder="+919876543210" />
            </div>
            <button type="submit" disabled={loading} className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50">
              {loading ? 'Sending...' : 'Send OTP'}
            </button>
          </form>
        ) : (
          <form onSubmit={handleVerifyOTP} className="p-6 space-y-6">
            <div className="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded text-sm text-center">
              Code sent to {formData.phoneNumber}
            </div>
            <div>
              <label className="block text-sm font-medium text-slate-700">Enter 6-digit OTP</label>
              <input type="text" name="otp" required value={formData.otp} onChange={handleChange} className="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 py-2 px-3 border text-center text-lg tracking-widest" placeholder="• • • • • •" maxLength="6" />
            </div>
            <button type="submit" disabled={loading} className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50">
              {loading ? 'Verifying...' : 'Verify & Register'}
            </button>
            <div className="text-center">
              <button type="button" onClick={() => setStep(1)} className="text-sm text-primary-600 hover:text-primary-500">
                Change phone number
              </button>
            </div>
          </form>
        )}
      </div>
    </div>
  );
}
