<template>
	<view class="page">
		<view class="status_bar" :style="{ backgroundColor: primary_color }">
			<!-- 状态栏占位 -->
		</view>
		<view class="container" :style="root_style">
			<image class="avatar" :src="profile.avatar" mode="scaleToFill"></image>
			<view class="share-group">
				<view class="share-btn" @tap="callPhone">
					<uni-icons type="phone-filled" :size="share_icon_size" :color="primary_color"></uni-icons>
				</view>
				<view class="share-btn" @tap="openLocation">
					<uni-icons type="location-filled" :size="share_icon_size" :color="primary_color"></uni-icons>
				</view>
				<view class="share-btn" @tap="openShareDialog">
					<uni-icons type="undo-filled" :size="share_icon_size" :color="primary_color"></uni-icons>
				</view>
				<!-- #ifdef MP-WEIXIN -->
				<view class="share-btn shake" :class="{ 'shake-constant': contact_me_shake }">
					<button type="default" class="btn" hover-class="btn-hover" open-type="contact">
						<uni-icons type="chat-filled" :size="share_icon_size" :color="primary_color"></uni-icons>
					</button>	
				</view>
				<!-- #endif -->
			</view>

			<!-- 底部分享弹窗 -->
			<!-- #ifdef MP-WEIXIN -->
			<uni-popup ref="popup" type="bottom">
				<view class="share-box">
					<button open-type="share" class="icon-text btn">
						<uni-icons type="weixin" size="50" :color="primary_color"></uni-icons>
						<text>分享朋友</text>						
					</button>
					<button class="icon-text btn" @tap="isShowPainter=true">
						<uni-icons type="image-filled" size="50" :color="primary_color"></uni-icons>
						<text>生成海报</text>					
					</button>
				</view>
			</uni-popup>			
			<!-- #endif -->
			<view class="content-box">
				<view class="header">
					<view class="title-box">
						<text class="title">{{profile.name}}</text>
						<text class="subtitle">{{profile.position}}</text>
					</view>
					<view class="tags-box">
						<text class="tag" v-for="item in profile.tags" :key="item">{{item}}</text>
					</view>
				</view>
				<view class="description">
					<view v-for="item in profile.description" :key="item">
						{{item}}
					</view>
				</view>
				<view class="contact-box">
					<view class="contact-line">
						<uni-icons type="phone-filled" :color="primary_color"></uni-icons>
						<text @tap="copyText" :data-text="profile.phone">{{profile.phone}}（同微信）-点击可复制</text>
					</view>
					<view class="contact-line">
						<uni-icons type="email-filled" :color="primary_color"></uni-icons>
						<text @tap="copyText" :data-text="profile.email">{{profile.email}}</text>
					</view>
					<view class="contact-line">
						<uni-icons type="info-filled" :color="primary_color"></uni-icons>
						<text @tap="copyText" :data-text="profile.company">{{profile.company}}</text>
					</view>
					<view class="contact-line">
						<uni-icons type="paperclip" :color="primary_color"></uni-icons>
						<text @tap="copyText" :data-text="profile.website">{{profile.website}}</text>
					</view>
					<view class="contact-line">
						<uni-icons type="location-filled" :color="primary_color"></uni-icons>
						<text @tap="copyText" :data-text="profile.address">
							{{profile.address}}
						</text>
					</view>
					<view class="contact-line">
						<uni-icons type="cloud-download-filled" :color="primary_color"></uni-icons>
						<text @tap="isShowPainter=true" class="contact-btn">下载名片到手机</text>
					</view>


				</view> 

			</view>
		</view>
		<view class="overlayer" v-if="isShowPainter" 
			@touchmove.stop.prevent="touchmoveHandler"
			@touchend.stop="touchmoveHandler"
			@catchtouchmove="touchmoveHandler"
			@tap="isShowPainter=false" 
			>														
		</view>
		<view class="painter-box" v-if="isShowPainter" @longtap="saveImage">
			<l-painter
			  isRenderImage
              :isH5PathToBase64="true"
			  custom-style=""
			  :board="placard" 
			  @success="image_path = $event"
			/>
			<view class="painter-tips">长按保存到相册</view>
		</view>
	</view>

</template>

<script>
	import {
		setClipboardData
	} from '@/js_sdk/u-clipboard/index.js'
	import LPainter from '@/components/lime-painter/index.vue'
	import request from '@/utils/request'

	export default {
		components: {
			LPainter
		},
		data() {
			return {
                profile: {
                    name: '',
                    avatar: '',
                    position: '',
                    tags: [],
                    description: '',
                    phone: '',
                    email: '',
                    company: '',
                    website: '',
                    address: '',
                    theme_color: '',
                    latitude: 0,
                    longitude: 0  
                },
				href: '',
				primary_color: '#37538f', // 图标主题色
                root_style: '--primary-color: #37538f',
				share_icon_size: 35, // 分享按钮图标的大小
				contact_me_shake: false, // 联系我按钮的晃动
				phone: '15013245515',
				timer: null, // 按钮晃动定时器
				image_path: "",
				isShowPainter: false,
				
                // 屏幕宽度和高度做适配处理
                screenWidth: 400,
                screenHeight: 700,
                areaTop: 0,
			}
		},
        computed: {
            placard: function () {
                var width = this.screenWidth * 0.8;
                var height = width * 1.2;
                var left = ( this.screenHeight - width ) / 2;
                var top = this.top + 80;


                return  {
                    width: width + "px",
                    height: height + 'px',
                    background: '#F6F7FB',
                    views: [
                        {
                            type: 'view',
                            css: {
                                left: '10px',
                                top: '10px',
                                background: '#fff',
                                radius: '16rpx',
                                width: width - 20 + 'px',
                                height: height - 20 + 'px',
                                shadow: '0 20rpx 48rpx rgba(0,0,0,.05)'
                            }
                        },
                        {
                            type: 'image',
                            src: this.profile.avatar,
                            // src: '../../static/images/avatar.jpg',
                            mode: 'widthFix',
                            css: {
                                left: '40rpx',
                                top: '40rpx',
                                width: '84rpx',
                                height: '84rpx',
                                radius: '50%',
                                color: '#999'
                            }
                        },
                        {
                            type: 'text',
                            text: this.profile.name,
                            css: {
                                color: '#333',
                                left: '144rpx',
                                top: '40rpx',
                                fontSize: '32rpx',
                                fontWeight: 'bold'
                            }
                        },
                        {
                            type: 'text',
                            text: this.profile.position,
                            css: {
                                color: '#666',
                                left: '250rpx',
                                top: '50rpx',
                                fontSize: '24rpx'
                            }
                        },
                        {
                            type: 'text',
                            text: this.profile.tags.splice(0, 3).join(" | "),
                            css: {
                                color: '#666',
                                left: '144rpx',
                                top: '90rpx',
                                fontSize: '24rpx'
                            }
                        },
                        {
                            type: 'text',
                            text: '电话：' + this.profile.phone + '（同微信）',
                            css: {
                                color: '#666',
                                left: '60rpx',
                                top: '160rpx',
                                fontSize: '30rpx'
                            }
                        },
                        {
                            type: 'text',
                            text: '邮箱：' + this.profile.email,
                            css: {
                                color: '#666',
                                left: '60rpx',
                                top: '210rpx',
                                fontSize: '30rpx'
                            }
                        },
                        {
                            type: 'text',
                            text: '公司：' + this.profile.company,
                            css: {
                                color: '#666',
                                left: '60rpx',
                                top: '260rpx',
                                fontSize: '30rpx'
                            }
                        },
                        {
                            type: 'text',
                            text: '网址：' + this.profile.website,
                            css: {
                                color: '#666',
                                left: '60rpx',
                                top: '310rpx',
                                fontSize: '30rpx'
                            }
                        },
                        {
                            type: 'text',
                            text: '地址：' + this.profile.address,
                            css: {
                                color: '#666',
                                left: '60rpx',
                                top: '360rpx',
                                fontSize: '30rpx'
                            }
                        },
                        {
                            type: 'image',
                            src: '../../static/images/qrcode.jpg',
                            mode: 'widthFix',
                            css: {
                                left: width * 0.35 + 'px',
                                top: height * 0.68 + 'px',
                                width: '178rpx',
                                height: '178rpx'
                            }
                        }
                    ]
                };
            }
       },
		methods: {
			onLoad() {
                // 获取名片详细信息
                this.fetchProfile();
                
                // 获取屏幕宽度和高度
                uni.getSystemInfo({
                    success: (res) => {
                        this.screenWidth = res.safeArea.width;
                        this.screenHeight = res.safeArea.height;
                        this.areaTop = res.safeArea.top;
                    }
                });
                
                // 联系我图标晃动
				// #ifdef MP-WEIXIN
				this.timer = setInterval(() => {
					this.contactMeShake();
				}, 20 * 1000);				
				// #endif
			},
			onUnload() {
				// #ifdef MP-WEIXIN
				if (this.timer) {
					clearInterval(this.timer);
				}
				// #endif
			},
			/**
			 * 页面开启下拉刷新
			 */
			onPullDownRefresh() {
                this.fetchProfile();
			},
            
            fetchProfile() {
				const url = '/card/profile/details?desc=1';
				(new request).requestAll(url).then(res => {
                    this.profile = res;
                    this.primary_color = res.theme_color;
                    this.root_style = '--primary-color: ' + res.theme_color;
                    uni.stopPullDownRefresh();
				});                
            },
			/**
			 * 联系我按钮晃动1s时间
			 * 
			 */
			contactMeShake() {
				this.contact_me_shake = true
				setTimeout(() => this.contact_me_shake = false, 1000);
			},
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
			/**
			 * 调用电话界面
			 */
			callPhone() {
				uni.makePhoneCall({
					phoneNumber: this.phone,
				})
			},
			/**
			 * 打开安卓消防学院位置
			 */
			openLocation() {
				uni.openLocation({
					latitude: parseFloat(this.profile.latitude),
					longitude: parseFloat(this.profile.longitude),
					name: this.profile.company,
					address: this.profile.address,
					success: function() {
						console.log('success');
					},
                    fail: (error) => {
                        console.log(error);
                    }
				});
			},
			/**
			 * 打开底部分享弹窗
			 */
			openShareDialog() {
				// #ifdef H5
				// 直接调用海报生成
                this.isShowPainter=true
				// #endif
				// #ifdef MP-WEIXIN
				this.$refs.popup.open();
				// #endif
			},
			/**
			 * 保存海报
			 */
			saveImage() {
				console.log("测试乌拉拉", this.image_path);
                // #ifndef H5
                this.isShowPainter = false;
                uni.saveImageToPhotosAlbum({
                	filePath: this.image_path,
                	success(res) {
                		uni.showToast({
                			title: '已保存到相册',
                			icon: 'success',
                			duration: 2000
                		})
                	}
                })
                // #endif
                // #ifdef H5
                uni.showToast({
                    title: "H5暂不支持保存海报！",
                    icon: 'none'
                })
                // #endif

			},
			/**
			 * 遮罩层滑动阻止
			 */	
			touchmoveHandler() {
				return
			}
		}
	}
</script>

<style>
	.status_bar {
		height: var(--status-bar-height);
		width: 100%;
	}
	page {
		background-color: #37538f;
	}

	.container {
		padding: 10px 0;
		font-size: 14px;
		background-color: var(--primary-color);
		--gray-color: #888;
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
		top: 150rpx;
		right: 40rpx;
		z-index: 200;
	}

	.share-btn {
		background-color: #fff;
		border-radius: 50%;
		width: 90rpx;
		height: 90rpx;
		display: flex;
		align-items: center;
		justify-content: center;
		margin-bottom: 40rpx;
		box-shadow: 5rpx 5rpx 30rpx rgba(0, 0, 0, 0.3),
			-5rpx -5rpx 30rpx rgba(0, 0, 0, 0.3);
	}

	.share-btn /deep/.uni-icons {
		align-self: center;
		line-height: 90rpx;
	}
	.share-btn button {
		border-radius: 50%!important;
		height: 90rpx;
	}
	
	.share-box {
		display: flex;
		align-items: center;
		justify-content: space-around;
		padding: 20rpx 0 40rpx;
		background-color: #fff;
	}
	.icon-text {
		display: flex;
		flex-direction: column;
		align-items: center;
	}
	button.btn {
		border: none;
		border-radius: 0;
		background-color: #fff;
		padding:0rpx;
		margin:0rpx;		
	}
	button.btn::after{  
		border:none;
	}
	.btn-hover {
		background-color: #fff;
	}
	.icon-text uni-icons {
		height: 180rpx;
	}
	.icon-text text {
		margin-top: 10rpx;
		color: var(--primary-color);
	}
	
	.overlayer {
		position: fixed;
		left: 0;
		top: 0;
		right: 0;
		bottom: 0;
		z-index: 500;
		background-color: rgba(0, 0, 0, 0.6);
	}
	
	.painter-box {
		position: fixed; 
		left: 10%; 
		top: 10%;
		z-index: 600;
	}
	.painter-tips {
		color: white;
		text-align: center;
		margin-top: 10rpx;
	}

	/* 晃动动画 */
	.shake.shake-constant {
		animation-name: shake;
		animation-duration: 100ms;
		animation-timing-function: ease-in-out;
		animation-iteration-count: infinite;
	}

	@keyframes shake {
		2% {
			transform: translate(0.5px, -0.5px) rotate(-0.5deg);
		}

		4% {
			transform: translate(-0.5px, -0.5px) rotate(1.5deg);
		}

		6% {
			transform: translate(-1.5px, -0.5px) rotate(0.5deg);
		}

		8% {
			transform: translate(-1.5px, -1.5px) rotate(0.5deg);
		}

		10% {
			transform: translate(2.5px, -1.5px) rotate(1.5deg);
		}

		12% {
			transform: translate(0.5px, 1.5px) rotate(0.5deg);
		}

		14% {
			transform: translate(1.5px, 0.5px) rotate(-0.5deg);
		}

		16% {
			transform: translate(0.5px, -0.5px) rotate(0.5deg);
		}

		18% {
			transform: translate(2.5px, -0.5px) rotate(1.5deg);
		}

		20% {
			transform: translate(1.5px, -0.5px) rotate(0.5deg);
		}

		22% {
			transform: translate(0.5px, 1.5px) rotate(1.5deg);
		}

		24% {
			transform: translate(2.5px, -0.5px) rotate(0.5deg);
		}

		26% {
			transform: translate(-1.5px, 2.5px) rotate(-0.5deg);
		}

		28% {
			transform: translate(0.5px, -1.5px) rotate(-0.5deg);
		}

		30% {
			transform: translate(0.5px, -0.5px) rotate(-0.5deg);
		}

		32% {
			transform: translate(2.5px, 1.5px) rotate(1.5deg);
		}

		34% {
			transform: translate(-0.5px, -1.5px) rotate(0.5deg);
		}

		36% {
			transform: translate(-0.5px, 1.5px) rotate(0.5deg);
		}

		38% {
			transform: translate(0.5px, -0.5px) rotate(0.5deg);
		}

		40% {
			transform: translate(2.5px, 1.5px) rotate(1.5deg);
		}

		42% {
			transform: translate(-0.5px, 1.5px) rotate(0.5deg);
		}

		44% {
			transform: translate(-0.5px, -0.5px) rotate(-0.5deg);
		}

		46% {
			transform: translate(-1.5px, -0.5px) rotate(1.5deg);
		}

		48% {
			transform: translate(0.5px, 2.5px) rotate(0.5deg);
		}

		50% {
			transform: translate(0.5px, 0.5px) rotate(1.5deg);
		}

		52% {
			transform: translate(-0.5px, -1.5px) rotate(1.5deg);
		}

		54% {
			transform: translate(2.5px, 0.5px) rotate(0.5deg);
		}

		56% {
			transform: translate(2.5px, 2.5px) rotate(0.5deg);
		}

		58% {
			transform: translate(0.5px, -1.5px) rotate(0.5deg);
		}

		60% {
			transform: translate(-0.5px, 1.5px) rotate(-0.5deg);
		}

		62% {
			transform: translate(-0.5px, 0.5px) rotate(1.5deg);
		}

		64% {
			transform: translate(0.5px, -0.5px) rotate(-0.5deg);
		}

		66% {
			transform: translate(1.5px, 2.5px) rotate(-0.5deg);
		}

		68% {
			transform: translate(0.5px, -0.5px) rotate(1.5deg);
		}

		70% {
			transform: translate(-0.5px, 2.5px) rotate(-0.5deg);
		}

		72% {
			transform: translate(1.5px, -1.5px) rotate(-0.5deg);
		}

		74% {
			transform: translate(1.5px, 2.5px) rotate(-0.5deg);
		}

		76% {
			transform: translate(-0.5px, -0.5px) rotate(0.5deg);
		}

		78% {
			transform: translate(-0.5px, 0.5px) rotate(1.5deg);
		}

		80% {
			transform: translate(2.5px, 2.5px) rotate(0.5deg);
		}

		82% {
			transform: translate(1.5px, 0.5px) rotate(1.5deg);
		}

		84% {
			transform: translate(1.5px, -1.5px) rotate(1.5deg);
		}

		86% {
			transform: translate(2.5px, 1.5px) rotate(0.5deg);
		}

		88% {
			transform: translate(2.5px, 2.5px) rotate(-0.5deg);
		}

		90% {
			transform: translate(-1.5px, -1.5px) rotate(0.5deg);
		}

		92% {
			transform: translate(0.5px, -1.5px) rotate(0.5deg);
		}

		94% {
			transform: translate(0.5px, -0.5px) rotate(1.5deg);
		}

		96% {
			transform: translate(-0.5px, 0.5px) rotate(1.5deg);
		}

		98% {
			transform: translate(0.5px, -1.5px) rotate(0.5deg);
		}

		0%,
		100% {
			transform: translate(0, 0) rotate(0);
		}
	}
</style>
