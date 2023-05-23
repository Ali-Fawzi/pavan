import React, { useState } from 'react';
import { NavLink } from 'react-router-dom';
import logo from '../assets/logo192.png'
import {CgArrowRightO} from 'react-icons/cg'
const Sidebar = ({ links, children }) => {
    const [open, setOpen] = useState(false);
    return (
        <section className="flex h-screen w-screen">
            <div
                className={`${
                    open ? 'w-72' : 'w-20'
                } bg-gradient-to-br from-cyan-700 to-cyan-600 h-screen p-5 pt-8 relative duration-300`}
            >
                <button
                    className={`absolute cursor-pointer -right-2 top-9 bg-white border-cyan-600
          border-2 rounded-full duration-300 ${!open && 'rotate-180'}`}
                    onClick={() => setOpen(!open)}
                >
                    <CgArrowRightO/>
                </button>
                <div className="flex gap-x-4 items-center p-2">
                    <img
                        src={logo}
                        className={`cursor-pointer duration-500 h-7 ${
                            open && 'rotate-[360deg] h-7'
                        }`}
                        onClick={() => setOpen(!open)}
                        alt="logo"
                    />
                </div>
                <ul className="pt-4">
                    {links.map((link, index) => (
                        <NavLink to={link.path}>
                            <li
                                key={index}
                                className={`${
                                    link.gap ? 'mt-4' : 'mt-2'
                                } flex rounded-md p-2 cursor-pointer hover:bg-sky-600 text-gray-100 text-sm items-center ${
                                    window.location.pathname === link.path && 'bg-cyan-800'
                                }`}
                            >
                                <div className="flex">
                                    <link.icon className = 'm-1' />
                                    <span className={`${!open && 'hidden'} origin-left duration-200 ml-3`}>
                                      {link.label}
                                    </span>
                                </div>
                            </li>
                        </NavLink>
                        ))
                    }
                </ul>
            </div>
            <div className="w-full p-7 bg-slate-100">{children}</div>
        </section>
    );
};
export default Sidebar;