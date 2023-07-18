import { useRef, useState } from 'react'
import { useStateContext } from '../contexts/ContextProvider.jsx'
import axiosClient from "../axios-client.js"

export const Login = () => {
  
  const email_ref = useRef();
  const password_ref = useRef();

  const [errors, setErrors] = useState(null);
  const {setUser, setToken} = useStateContext();
  
  const onSubmit = (e) => {
    e.preventDefault();

    const payload = {
      email: email_ref.current.value,
      password: password_ref.current.value,
    }

    axiosClient.post('/login', payload)
      .then(({data}) => {
        setUser(data.user)
        setToken(data.user.token)
      })
      .catch(error => {
        const response = error.response;

        if (response && response.status === 422) {
          console.log(response.data.errors);
          setErrors(response.data.errors);
          console.log(response);
        }
      })

  }
  return (
    <div className="container d-flex align-items-center justify-content-center vh-100">
      <div className="card p-4">
        <h2 className="text-center mb-4">Login to your Account</h2>
        {errors && 
          <div className='alert'>
            {Object.keys(errors).map(key => (
              <p key={key}>{errors[key][0]}</p>
            ))}
          </div>
        }
        <form onSubmit={onSubmit}>
          <div className="mb-3">
            <label htmlFor="email" className="form-label">Email</label>
            <input ref={email_ref}
              type="email"
              className="form-control"
              id="email"
              required
            />
          </div>
          <div className="mb-3">
            <label htmlFor="password" className="form-label">Password</label>
            <input ref={password_ref}
              type="password"
              className="form-control"
              id="password"
              required
            />
          </div>
          <button type="submit" className="btn btn-primary">Login</button>
        </form>
      </div>
    </div>
  )
}
