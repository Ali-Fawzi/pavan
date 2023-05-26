import { useState } from 'react';
import Input from "../component/Input";
import logo from "../assets/logo192.png"
import {useNavigate} from "react-router-dom";
import jwtDecode from "jwt-decode";
import api from "../api/api";
const fields = [
    {
        id: "email",
        labelText: "Email",
        labelFor: "email",
        name: "email",
        type: "email",
        isRequired: true,
        placeholder: "Email address"
    },
    {
        id: "password",
        labelText: "Password",
        labelFor: "password",
        name: "password",
        type: "password",
        isRequired: true,
        placeholder: "Password"
    }
];

let fieldsState = {};
fields.forEach(field => fieldsState[field.id] = '');

async function loginUser(credentials) {
    try {
        const response = await api.post('/login', credentials);
        return response.data;
    } catch (error) {
        console.error("Error:", error);
    }
}

export default function Login() {
    const [loginState, setLoginState] = useState(fieldsState);
    const [isLoading, setIsLoading] = useState(false);
    const navigate = useNavigate();

    const handleChange = (e) => {
        setLoginState({ ...loginState, [e.target.id]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setIsLoading(true);
        const data = await loginUser(loginState);
        setIsLoading(false);
        if (data) {
            localStorage.setItem('access_token', data.access_token);
            const decodedToken = jwtDecode(data.access_token);
            const userRole = decodedToken.role;

            switch (userRole) {
                case 'admin':
                    navigate('/admin-dashboard');
                    break;
                case 'doctor':
                    navigate('/doctor-dashboard');
                    break;
                case 'secretary':
                    navigate('/secretary-dashboard');
                    break;
                default:
                    console.error('Unknown user role:', userRole);
            }
        } else {
            setLoginState(fieldsState);
        }
    };
    return (
        <div className="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
            <div className="sm:mx-auto sm:w-full sm:max-w-md">
                <div className="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                    <form className="mt-8 space-y-6 mb-20" onSubmit={handleSubmit}>
                        <div>
                            <img src={logo} alt="logo" className="mx-auto w-12"/>
                            <p className='text-center text-blue-400 mt-1 mb-16'>Pavan Clinic</p>
                        </div>
                        <div className="-space-y-px">
                            {
                                fields.map(field =>
                                    <Input
                                        key={field.id}
                                        handleChange={handleChange}
                                        value={loginState[field.id]}
                                        labelText={field.labelText}
                                        labelFor={field.labelFor}
                                        id={field.id}
                                        name={field.name}
                                        type={field.type}
                                        isRequired={field.isRequired}
                                        placeholder={field.placeholder}
                                    />
                                )
                            }
                        </div>
                        <button
                            type="submit"
                            className="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-400 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            disabled={isLoading}
                        >
                            {isLoading ? 'Loading...' : 'Submit'}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    );
}