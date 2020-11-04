<template>
	<view>
		<view class="status_bar">
			<!-- 状态栏占位 -->
		</view>
		<view class="container">
			<image class="avatar" src="../../static/images/avatar.png" mode="scaleToFill"></image>
            <view class="share-group">
                <view class="share-btn" @tap="callPhone">
                    <uni-icons type="phone-filled" :size="share_icon_size" :color="primary_color"></uni-icons>
                </view>
                <view class="share-btn">
                    <uni-icons type="location-filled" :size="share_icon_size" :color="primary_color"></uni-icons>
                </view>
                <view class="share-btn">
                    <uni-icons type="undo-filled" :size="share_icon_size" :color="primary_color"></uni-icons>
                </view>
            </view>
			<view class="content-box">
				<view class="header">
					<view class="title-box">
						<text class="title">李勇</text>
						<text class="subtitle">产品经理 技术顾问</text>						
					</view>
					<view class="tags-box">
						<text class="tag">小程序 融创品牌APP</text>
						<text class="tag">会员电商</text>
						<text class="tag">代理分销</text>
						<text class="tag">社交即时通讯</text>
					</view>
				</view>
				<view class="description">
					<view>
						小瓶科技. 为企业提供专业的、互联网化、可定制的、高端项目开发。能够做到私有化部署、独立商业正版、100％项目实施成功率。
						小瓶科技: www.pbottle.com
					</view>
   
					<view>
						小瓶云 是北京小瓶科技推出的应用级云服务平台，具有开通即用、节约时间成本、节约资金和人力成本、免费升级等特点。小瓶云商平台是最新适合移动互联网时代的平台，一键在线生成服务产品，依赖于手机应用提供服务，具有扁平快的特点。
					</view>
                    
					<view>
						百度搜索“小瓶科技” “小瓶云”了解更多…..
					</view>
				</view>
				<view class="contact-box">
                    <view class="contact-line">
                        <uni-icons type="phone-filled" :color="primary_color"></uni-icons>
                        <text @tap="copyText" data-text="18801079534">18801079534 （同微信）-点击可复制</text>
                    </view>
                    <view class="contact-line">
                        <uni-icons type="email-filled" :color="primary_color"></uni-icons>
                        <text @tap="copyText" data-text="1leo＠pbottle.com">1leo＠pbottle.com</text> 
                    </view>
                    <view class="contact-line">
                        <uni-icons type="info-filled" :color="primary_color"></uni-icons>
                        <text @tap="copyText" data-text="北京小瓶科技有限公司">北京小瓶科技有限公司</text> 
                    </view>
                    <view class="contact-line">
                        <uni-icons type="paperclip" :color="primary_color"></uni-icons>
                        <text @tap="copyText" data-text="https://www.pbottle.com/">https://www.pbottle.com/</text>
                    </view>
                    <view class="contact-line">
                        <uni-icons type="location-filled" :color="primary_color"></uni-icons>
                        <text @tap="copyText" data-text="北京市朝阳区高碑店吉里国际艺术区w6d01">北京市朝阳区高碑店吉里国际艺术区w6d01</text>
                    </view>
                    <view class="contact-line">
                        <uni-icons type="cloud-download-filled" :color="primary_color"></uni-icons>
                        <text class="contact-btn">下载名片到手机</text> 
                    </view>
				</view>
				
			</view>
		</view>		
	</view>

</template>

<script>
    import { setClipboardData } from '@/js_sdk/u-clipboard/index.js'
    
	export default {
		data() {
			return {
				href: '',
                primary_color: '#37538f', // 图标主题色
                share_icon_size: 30, // 分享按钮图标的带下
                phone: '18801079534',
			}
		},
		methods: {
            /**
             * 复制文本
             * 
             * @param {Object} event
             */
            copyText(event) {
                var text = event.currentTarget.dataset.text;
                if (text) {
                    setClipboardData(text)
                        .then(res => {
                            // #ifdef H5
                            uni.showToast({
                                title: "已复制到剪切板",
                            })                              
                            // #endif
                        })
                        .catch(error => {
                            console.log(error);
                        })
                }
            },
            callPhone() {
              uni.makePhoneCall({
                  phoneNumber: this.phone,
              })  
            },
		}
	}
</script>

<style>
	.status_bar {
		height: var(--status-bar-height);
		widows: 100%;
	}
	.container {
		padding: 10px 0;
		font-size: 14px;
		line-height: 24px;
		background-color: var(--primary-color);
        --gray-color: #888;
        --primary-color: #37538f;
	}
	.avatar {
		width: 60%;
		height: 440rpx;
		border-radius: 20rpx;
		box-shadow: 5rpx 5rpx 30rpx rgba(0, 0, 0, 0.5),
            -5rpx -5rpx 30rpx rgba(0, 0, 0, 0.5);
        margin-left: 60rpx;
        position: relative;
        z-index: 100;
	}
    
    .content-box {
        margin: 0 20rpx;
        border-radius: 20rpx;
        background-color: #fff;
        padding: 180rpx 30rpx 0;
        position: relative;
        top: -140rpx;
    }
    
    .title-box {
        margin-bottom: 5px;
    }
    .title-box .title {
        font-weight: 600;
        font-size: 40rpx;
        color: #808080;
        margin-right: 10px;
    }
    .title-box .subtitle {
        color: var(--gray-color);
    }
    
    .tags-box .tag {
        position: relative;
        margin-left: 10rpx;
        margin-right: 10rpx;
        font-size: 25rpx;
        color: var(--gray-color);
    }
    .tags-box .tag:first-child {
        margin-left: 0;
    }
    .tags-box .tag:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 10%;
        right: -10rpx;
        height: 80%;
        width: 1rpx;
        background-color: var(--gray-color);
    }
    
    .description {
        color: var(--primary-color);
        font-size: 25rpx;
        margin-top: 10rpx;
        line-height: 1.5;
    }
    
    .description view {
        margin-bottom: 40rpx;
    }
    
    .contact-box {
        margin: 40rpx 0rpx 0;
        padding: 40rpx 10rpx;
        position: relative;
    }
    .contact-box::after {
        content: '';
        position: absolute;
        height: 1rpx;
        width: 100%;
        top: 0;
        left: 0;
        background-color: #ddd;
    }
    .contact-line {
        font-size: 25rpx;
        color: var(--gray-color);
        margin-left: 15rpx;
        margin-bottom: 8rpx;
    }
    .contact-line text:last-child {
        margin-left: 15rpx;
    }
    .contact-btn {
        background-color: var(--primary-color);
        padding: 10rpx 20rpx;
        color: white;
        border-radius: 15rpx;
    }
    
    .share-group {
        position: fixed;
        top: 100rpx;
        right: 40rpx;
        z-index: 200;
    }
    .share-btn {
        background-color: #fff;
        border-radius: 50%;
        width: 90rpx;
        height: 90rpx;
        line-height: 90rpx;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 40rpx;
		box-shadow: 5rpx 5rpx 30rpx rgba(0, 0, 0, 0.3),
            -5rpx -5rpx 30rpx rgba(0, 0, 0, 0.3);
    }
</style>
