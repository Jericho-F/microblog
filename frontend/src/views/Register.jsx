import { useRef, useState } from 'react'
import { useStateContext } from '../contexts/ContextProvider.jsx'
import axiosClient from "../axios-client.js"

const Register = () => {

  const first_name_ref = useRef();
  const last_name_ref = useRef();
  const username_ref = useRef();
  const email_ref = useRef();
  const birthdate_ref = useRef();
  const mobile_no_ref = useRef();
  const lot_block_ref = useRef();
  const street_ref = useRef();
  const city_ref = useRef();
  const province_ref = useRef();
  const country_ref = useRef();
  const zip_code_ref = useRef();
  const password_ref = useRef();
  const password_confirmation_ref = useRef();
  const [errors, setErrors] = useState(null);
  const {setUser, setToken} = useStateContext();
  
  const onSubmit = (e) => {
    e.preventDefault();

    const payload = {
      first_name: first_name_ref.current.value,
      last_name: last_name_ref.current.value,
      username: username_ref.current.value,
      email: email_ref.current.value,
      birthdate: birthdate_ref.current.value,
      mobile_no: mobile_no_ref.current.value,
      lot_block: lot_block_ref.current.value,
      street: street_ref.current.value,
      city: city_ref.current.value,
      province: province_ref.current.value,
      country: country_ref.current.value,
      zip_code: zip_code_ref.current.value,
      password: password_ref.current.value,
      password_confirmation: password_confirmation_ref.current.value
    }

    axiosClient.post('/register', payload)
      .then(({data}) => {
        setUser(data.user)
        setToken(data.token)
        console.log(data);
      })
      .catch(error => {
        const response = error.response;

        if (response && response.status === 422) {
          console.log(response.data.errors);
          setErrors(response.data.errors);
        }
      })

    console.log(payload);
  }
  return (
    <div className="container d-flex align-items-center justify-content-center mt-5">
      <div className="card p-4 bg-black shadow p-3 mb-5 bg-white rounded">
        <h2 className="text-center mb-4">Registration Form</h2>
        {errors && 
          <div className='alert'>
            {Object.keys(errors).map(key => (
              <p key={key}>{errors[key][0]}</p>
            ))}
          </div>
        }
        <form onSubmit={onSubmit}>
          <div className="row mb-3">
            <div className="col-md-4">
              <label htmlFor="first_name" className="form-label">First Name</label>
              <input ref={first_name_ref} 
                type="text"
                className="form-control"
                id="first_name"
              />
            </div>
            <div className="col-md-4">
              <label htmlFor="last_name" className="form-label">Last Name</label>
              <input ref={last_name_ref}
                type="text"
                className="form-control"
                id="last_name"
              />
            </div>
            <div className="col-md-4">
              <label htmlFor="username" className="form-label">Username</label>
              <input ref={username_ref}
                type="text"
                className="form-control"
                id="username"
              />
            </div>
          </div>
          <div className="row mb-3">
            <div className="col-md-4">
              <label htmlFor="email" className="form-label">Email</label>
              <input ref={email_ref}
                type="text"
                className="form-control"
                id="email"
              />
            </div>
            <div className="col-md-4">
              <label htmlFor="birthdate" className="form-label">Birthdate</label>
              <input ref={birthdate_ref}
                type="date"
                className="form-control"
                id="birthdate"
              />
            </div>
            <div className="col-md-4">
              <label htmlFor="mobile_no" className="form-label">Mobile Number</label>
              <input ref={mobile_no_ref}
                type="tel"
                className="form-control"
                id="mobile_no"
              />
            </div>
          </div>
          <div className="row mb-3">
            <div className="col-md-4">
              <label htmlFor="lot_block" className="form-label">Lot/Block</label>
              <input ref={lot_block_ref}
                type="text"
                className="form-control"
                id="lot_block"
              />
            </div>
            <div className="col-md-4">
              <label htmlFor="street" className="form-label">Street</label>
              <input ref={street_ref}
                type="text"
                className="form-control"
                id="street"
              />
            </div>
            <div className="col-md-4">
              <label htmlFor="city" className="form-label">City</label>
              <input ref={city_ref}
                type="text"
                className="form-control"
                id="city"
              />
            </div>
          </div>
          <div className="row mb-3">
            <div className="col-md-4">
              <label htmlFor="province" className="form-label">Province</label>
              <input ref={province_ref}
                type="text"
                className="form-control"
                id="province"
              />
            </div>
            <div className="col-md-4">
              <label htmlFor="country" className="form-label">Country</label>
              <input ref={country_ref}
                type="text"
                className="form-control"
                id="country"
              />
            </div>
            <div className="col-md-4">
              <label htmlFor="zip_code" className="form-label">Zip Code</label>
              <input ref={zip_code_ref}
                type="text"
                className="form-control"
                id="zip_code"
              />
            </div>
          </div>
          <div className="row mb-2">
            <div className="col-md-6">
              <label htmlFor="password" className="form-label">Password</label>
              <input ref={password_ref}
                type="password"
                className="form-control"
                id="password"
              />
            </div>
            <div className="col-md-6">
              <label htmlFor="password_confirmation" className="form-label">Confirm Password</label>
              <input ref={password_confirmation_ref}
                type="password"
                className="form-control"
                id="password_confirmation"
              />
            </div>
          </div>
          <div className='row mt-4'>
            <button type="submit" className="btn btn-primary">Register</button>
          </div>
        </form>
      </div>
    </div>
  )
}

export default Register