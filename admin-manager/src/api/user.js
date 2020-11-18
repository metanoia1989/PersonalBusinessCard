import request from '@/utils/request'

export function login(data) {
  return request({
    url: '/auth/user/login',
    method: 'post',
    data
  })
}

export function getInfo() {
  return request({
    url: '/auth/user/me',
    method: 'get',
  })
}

export function logout() {
  return request({
    url: '/auth/user/logout',
    method: 'post'
  })
}

export function fetchProfile() {
  return request({
    url: '/card/profile/details',
    method: 'get',
  })
}

export function updateProfile(data) {
  return request({
    url: '/card/profile/update',
    method: 'post',
    data: data
  })
}

