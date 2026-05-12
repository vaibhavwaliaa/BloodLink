import { useEffect } from 'react';
import { useNavigate, useSearchParams } from 'react-router-dom';
import axios from 'axios';

export default function OAuthCallback() {
  const navigate = useNavigate();
  const [searchParams] = useSearchParams();

  useEffect(() => {
    const token = searchParams.get('token');
    
    if (token) {
      // In a real app, you'd store this in context or secure cookie
      localStorage.setItem('bloodlink_token', token);
      
      // Configure axios to use this token for future requests
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      
      const intent = localStorage.getItem('auth_intent');
      if (intent === 'donor') {
        localStorage.removeItem('auth_intent');
        navigate('/register-donor');
      } else {
        navigate('/dashboard');
      }
    } else {
      console.error('No token found in URL');
      navigate('/');
    }
  }, [navigate, searchParams]);

  return (
    <div className="flex-1 flex justify-center items-center py-12">
      <div className="text-center">
        <div className="inline-block animate-spin rounded-full h-12 w-12 border-4 border-primary-600 border-t-transparent"></div>
        <p className="mt-4 text-lg font-medium text-slate-700">Authenticating...</p>
      </div>
    </div>
  );
}
