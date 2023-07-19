import React, { useEffect } from 'react'
import { Navigate, Outlet, Link } from 'react-router-dom'
import { useStateContext } from '../contexts/ContextProvider.jsx'
import axiosClient from '../axios-client.js'

const DefaultLayout = () => {   
    const {user, token, setUser, setToken} = useStateContext()

    if (!token) {
        return <Navigate to="/login"/>
    }

    const onLogout = (e) => {
        e.preventDefault();

        axiosClient.post('/logout')
            .then(() => {
                setUser({})
                setToken(null)
            })
    }

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await axiosClient.get('/user');
                setUser(response.data);
            } catch (error) {
                console.error(error);
            }
        };
      
        fetchData();
    }, []);
    return (
        <div id="defaultLayout">
            <nav className="navbar navbar-expand-lg bg-body-tertiary">
                <div className="container">
                    <a className="navbar-brand" href="#">Microblog</a>
                    <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span className="navbar-toggler-icon"></span>
                    </button>
                    <div className="collapse navbar-collapse justify-content-end" id="navbarNav">
                        <ul className="navbar-nav">
                            <li className="nav-item dropdown">
                            <a className="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {user?.userProfile?.first_name}
                            </a>
                            <ul className="dropdown-menu dropdown-menu-end">
                                <li><a className="dropdown-item" href="#">Profile</a></li>
                                <li><a className="dropdown-item" href="#">Change password</a></li>
                                <li><a className="dropdown-item" onClick={onLogout} href="#">Logout</a></li>
                            </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <Outlet />
        </div>
    )
}

export default DefaultLayout