import React, { useEffect, useRef, useState } from 'react'
import axiosClient from '../axios-client';
import { useStateContext } from '../contexts/ContextProvider';

const Dashboard = () => {

  const content_ref = useRef();
  const image_ref = useRef();
  
  const [posts, setPosts] = useState(null);
  const {user, setUser,} = useStateContext();
  const [errors, setErrors] = useState(null);
  useEffect(() => {
    fetchData()
  }, [])

  const fetchData = async () => {
    try {
        const response = await axiosClient.get('/user');
        setUser(response.data);
        setPosts(response.data.posts);
        // response.data.posts.map(r => {
        //   console.log(r.content);
        // })
        // console.log(response.data.posts);
    } catch (error) {
        console.error(error);
    }
  };

  const onSubmit = (e) => {
    e.preventDefault();
  
    const payload = new FormData();
    console.log(payload);
    payload.append('content', content_ref.current.value);

    if (image_ref.current.files[0]) {
      payload.append('image', image_ref.current.files[0]);
    }
  
    axiosClient.post('/post', payload)
      .then(({data}) => {
        console.log(data);
      })
      .catch(error => {
        const response = error.response;
  
        if (response && response.status === 422) {
          console.log(response.data.errors);
          setErrors(response.data.errors.image[0]);
          console.log(response.data.errors.image[0]);
        }
      });
  };

  return (
    <div className='container'>
      <div className='d-flex justify-content-center'>
        <div className="col-md-7">
          <div className="post card mt-3">
              <div className="card-header ">
                <img src="" width="45" height="45" alt="Profile pic" />
                <span>{user?.userProfile?.first_name}</span>
              </div>
              <div className="card-body">
                  <div>
                      <form onSubmit={onSubmit} encType="multipart/form-data" id="postForm">
                          <textarea ref={content_ref} id="content" className="post-input form-control " name="content" rows="3" placeholder="What's on your mind?" autoFocus></textarea>
                          <div><span id="char_count">0</span>/140</div>
                          <div className="mt-3">
                              <label htmlFor="image" className="form-label">Upload Image:</label>
                              <input ref={image_ref} type="file" className="form-control" name="image" id="image" />
                              <span id="fileSizeError" className="text-danger">
                                {errors && 
                                  <div className='alert is-invalid'>
                                    <span>{errors}</span>
                                  </div>
                                }
                              </span>
                              <div className="img-preview-container mt-2">
                                  <img id="imagePreview" src="#" alt="Image Preview"  />
                              </div>
                          </div>
                          <div className="mt-3 d-flex justify-content-center">
                              <button type="submit" id="postBtn" className="btn btn-primary post w-100">Post</button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
        </div>
      </div>
      <div>
        {posts && (
          <div>
            {posts.map(p => (
              <div key={p.id}>{p.content}</div>
            ))}
          </div>
        )}
      </div>
    </div>
  )
}

export default Dashboard