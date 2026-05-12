import { BrowserRouter as Router, Routes, Route, Link, useNavigate } from 'react-router-dom';
import { useState, useEffect } from 'react';
import Home from './pages/Home';
import Dashboard from './pages/Dashboard';
import CreateRequest from './pages/CreateRequest';
import OAuthCallback from './pages/OAuthCallback';
import DonorRegistration from './pages/DonorRegistration';

function NavBar() {
  const [user, setUser] = useState(null);
  const navigate = useNavigate();

  useEffect(() => {
    const token = localStorage.getItem('bloodlink_token');
    if (token) {
      try {
        // Decode JWT payload (no verification needed client-side)
        const payload = JSON.parse(atob(token.split('.')[1]));
        setUser(payload);
      } catch (e) {
        localStorage.removeItem('bloodlink_token');
      }
    }
  }, []);

  const handleLogin = () => {
    window.location.href = `${import.meta.env.VITE_API_URL}/auth/google`;
  };

  const handleLogout = () => {
    localStorage.removeItem('bloodlink_token');
    setUser(null);
    navigate('/');
  };

  const handleRegisterDonor = () => {
    const token = localStorage.getItem('bloodlink_token');
    if (token) {
      // Already logged in — skip OAuth, go straight to donor form
      navigate('/register-donor');
    } else {
      localStorage.setItem('auth_intent', 'donor');
      window.location.href = `${import.meta.env.VITE_API_URL}/auth/google`;
    }
  };

  return (
    <header className="bg-white shadow-sm border-b border-slate-200 sticky top-0 z-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between h-16">
          <div className="flex items-center">
            <Link to="/" className="flex-shrink-0 flex items-center text-primary-600 font-bold text-2xl tracking-tight">
              <span className="text-3xl mr-2">🩸</span> BloodLink
            </Link>
            <div className="hidden sm:ml-8 sm:flex sm:space-x-8">
              <Link to="/dashboard" className="border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                Dashboard
              </Link>
              <Link to="/requests/new" className="border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                Request Blood
              </Link>
            </div>
          </div>
          <div className="flex items-center space-x-3">
            {user ? (
              <>
                <span className="text-sm text-slate-600 font-medium hidden sm:block">
                  👤 {user.email?.split('@')[0]}
                </span>
                <button onClick={handleRegisterDonor} className="bg-primary-50 border border-primary-200 text-primary-700 hover:bg-primary-100 px-3 py-2 rounded-md text-sm font-medium transition-all">
                  ❤️ Be a Donor
                </button>
                <button onClick={handleLogout} className="bg-slate-100 text-slate-700 hover:bg-slate-200 px-3 py-2 rounded-md text-sm font-medium transition-all">
                  Logout
                </button>
              </>
            ) : (
              <button onClick={handleLogin} className="bg-primary-600 text-white hover:bg-primary-700 px-4 py-2 rounded-md text-sm font-medium shadow-sm transition-all hover:shadow">
                Login / Register
              </button>
            )}
          </div>
        </div>
      </div>
    </header>
  );
}

function App() {
  return (
    <Router>
      <div className="min-h-screen bg-slate-50 flex flex-col font-sans">
        <NavBar />
        <main className="flex-1 flex flex-col w-full">
          <Routes>
            <Route path="/" element={<Home />} />
            <Route path="/dashboard" element={<Dashboard />} />
            <Route path="/requests/new" element={<CreateRequest />} />
            <Route path="/oauth/callback" element={<OAuthCallback />} />
            <Route path="/register-donor" element={<DonorRegistration />} />
          </Routes>
        </main>
      </div>
    </Router>
  );
}

export default App;

