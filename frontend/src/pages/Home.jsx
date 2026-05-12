import { Link } from 'react-router-dom';

export default function Home() {
  return (
    <div className="flex-1 flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 py-12 bg-gradient-to-br from-primary-50 to-white">
      <div className="max-w-3xl w-full text-center space-y-8">
        <h1 className="text-5xl font-extrabold text-slate-900 tracking-tight sm:text-6xl">
          Save Lives With <span className="text-primary-600">BloodLink</span>
        </h1>
        <p className="mt-4 text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed">
          The fastest way to find blood donors in an emergency. Join our community of life-savers and make a real difference today.
        </p>
        
        <div className="flex flex-col sm:flex-row justify-center gap-4 mt-10">
          <Link to="/requests/new" className="inline-flex justify-center items-center px-8 py-4 border border-transparent text-lg font-medium rounded-xl text-white bg-primary-600 hover:bg-primary-700 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
            <span className="mr-2">🚨</span> Request Blood Now
          </Link>
          <button onClick={() => {
            localStorage.setItem('auth_intent', 'donor');
            window.location.href = `${import.meta.env.VITE_API_URL}/auth/google`;
          }} className="inline-flex justify-center items-center px-8 py-4 border-2 border-primary-600 text-lg font-medium rounded-xl text-primary-600 bg-transparent hover:bg-primary-50 shadow-sm hover:shadow transition-all cursor-pointer z-10 relative">
            <span className="mr-2">❤️</span> Register as Donor
          </button>
        </div>
        
        <div className="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
          <div className="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col items-center">
            <div className="bg-primary-100 p-4 rounded-full mb-4">
              <span className="text-2xl">📍</span>
            </div>
            <h3 className="text-lg font-bold text-slate-900">Geo-Location Match</h3>
            <p className="text-slate-500 text-center mt-2">Find donors exactly where you need them using real-time mapping.</p>
          </div>
          <div className="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col items-center">
            <div className="bg-primary-100 p-4 rounded-full mb-4">
              <span className="text-2xl">⚡</span>
            </div>
            <h3 className="text-lg font-bold text-slate-900">Instant Alerts</h3>
            <p className="text-slate-500 text-center mt-2">Push notifications and SMS instantly sent to matching donors nearby.</p>
          </div>
          <div className="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col items-center">
            <div className="bg-primary-100 p-4 rounded-full mb-4">
              <span className="text-2xl">🛡️</span>
            </div>
            <h3 className="text-lg font-bold text-slate-900">Verified Hospitals</h3>
            <p className="text-slate-500 text-center mt-2">Trusted requests verified through hospital partnerships.</p>
          </div>
        </div>
      </div>
    </div>
  );
}
