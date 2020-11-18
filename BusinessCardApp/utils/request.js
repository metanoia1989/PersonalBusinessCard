
if (process.env.NODE_ENV === 'development') {
    // 开发环境
    const app_url = 'http://azxfcard.test';
} else {
    // 生产环境
    const app_url = 'http://card.anzhuoxfpx.com';
}

class request {
    constructor() {
      this._header = {
        'content-type': 'application/x-www-form-urlencoded',
      }
    }
  
    /**
     * 设置统一的异常处理
     */
    setErrorHandler(handler) {
      this._errorHandler = handler;
    }
  
    /**
     * GET类型的网络请求
     */
    getRequest(url, data, header = this._header) {
      return this.requestAll(url, data, header, 'GET')
    }
  
    /**
     * DELETE类型的网络请求
     */
    deleteRequest(url, data, header = this._header) {
      return this.requestAll(url, data, header, 'DELETE')
    }
  
    /**
     * PUT类型的网络请求
     */
    putRequest(url, data, header = this._header) {
      return this.requestAll(url, data, header, 'PUT')
    }
  
    /**
     * POST类型的网络请求
     */
    postRequest(url, data, header = this._header) {
      return this.requestAll(url, data, header, 'POST')
    }
  
    /**
     * 网络请求
     */
    requestAll(url, data, header, method) {
      return new Promise((resolve, reject) => {
        uni.request({
          url: app_url + url,
          data: data,
          header: header,
          method: method,
          success: (res => {
            if (res.is_success == 'success') {
              //200: 服务端业务处理正常结束
              resolve(res)
            } else {
              uni.showModal({
                title: '提示',
                content: '登陆超时，请关闭程序重新登陆',
                showCancel: false,
              })
            }
          }),
          fail: (res => {
            console.log('身份验证失败，请重新登陆')
          })
        })
      })
    }
  }
  
export default request;