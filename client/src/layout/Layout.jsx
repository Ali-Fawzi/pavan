import React from 'react';
import Sidebar from './Sidebar';
import jwt_decode from "jwt-decode";
import {AiOutlineHome} from 'react-icons/ai'
import {FaUsers} from 'react-icons/fa'
import {SlWallet} from 'react-icons/sl'
import {AiFillSetting} from 'react-icons/ai'
const getSidebarLinks = (userRole) => {
    switch (userRole) {
        case 'admin':
            return [
                { label: 'Dashboard', path: '/admin-dashboard', icon:AiOutlineHome , gap: false },
                { label: 'Users', path: '/admin-users', icon:FaUsers , gap: false },
                { label: 'Expenses', path: '/admin-expenses', icon:SlWallet , gap: false },
                { label: 'Account', path: '/account', icon: AiFillSetting, gap: false},
            ];
        case 'doctor':
            return [
                { label: 'Dashboard', path: '/doctor-dashboard', icon:AiOutlineHome , gap: false },
            ];
        case 'secretary':
            return [
                { label: 'Dashboard', path: '/secretary-dashboard', icon:AiOutlineHome , gap: false },
            ];
        default:
            return [];
    }
};

const Layout = ({ children }) => {
    const decodedToken = jwt_decode(localStorage.getItem("access_token"));
    const sidebarLinks = getSidebarLinks(decodedToken.role);
    return (
        <div className="grid min-h-screen grid-rows-header bg-slate-50">
            <div className="grid md:grid-cols-sidebar">
                <Sidebar links={sidebarLinks} children={children}/>
            </div>
        </div>
    );
};


export default Layout;
