import { Navigate, Outlet, Link } from 'react-router-dom'
import { useStateContext } from '../contexts/ContextProvider'

const GuestLayout = () => {
  const {token} = useStateContext();

  if (token) {
    return <Navigate to="/" />
  }

  return (
    <div>
      <nav className="navbar navbar-expand-lg bg-body-tertiary">
          <div className="container">
              <a className="navbar-brand" href="#">Microblog</a>
              <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                  <span className="navbar-toggler-icon"></span>
              </button>
              <div className="collapse navbar-collapse" id="navbarNav">
                  <ul className="navbar-nav">
                      <li className="nav-item">
                          <Link className="nav-link active" aria-current="page" to="/signup">Signup</Link>
                      </li>
                      <li className="nav-item">
                          <Link className="nav-link active" aria-current="page" to="/login">Login</Link>
                      </li>
                  </ul>
              </div>
          </div>
      </nav>
      <Outlet />
    </div>
  )
}

export default GuestLayout