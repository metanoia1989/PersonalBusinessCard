var app_url;
if (process.env.NODE_ENV === 'development') {
    // 开发环境
    app_url = 'http://azxfcard.test';
} else {
    // 生产环境
    app_url = 'https://yang.anzhuoxfpx.com';
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
            const { data } = res;
            console.log(data);
            if (data.code == 0) {
              //200: 服务端业务处理正常结束
              resolve(data.data)
            } else {
              uni.showModal({
                title: '提示',
                content: '登陆超时，请关闭程序重新登陆',
                showCancel: false,
              })
            }
          }),
          fail: (res => {
            console.log(res)
            uni.showToast({
                title: "服务器繁忙！"
            });
          })
        })
      })
    }
  }
  
export default request;