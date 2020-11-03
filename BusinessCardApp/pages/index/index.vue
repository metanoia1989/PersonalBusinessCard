<template>
	<view>
		<view class="status_bar">
			<!-- 状态栏占位 -->
		</view>
		<view class="container">
			<image class="avatar" src="../../static/images/avatar.png" mode="scaleToFill"></image>
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
					<text>
						小瓶科技. 为企业提供专业的、互联网化、可定制的、高端项目开发。
						能够做到私有化部署、独立商业正版、100％项目实施成功率。
						小瓶科技: www.pbottle.com
					</text>
					<text>
						小瓶云 是北京小瓶科技推出的应用级云服务平台，
						具有开通即用、节约时间成本、节约资金和人力成本、免费升级等特点。
						小瓶云商平台是最新适合移动互联网时代的平台，一键在线生成服务产品，
						依赖于手机应用提供服务，具有扁平快的特点。
					</text>
					<text>
						百度搜索“小瓶科技” “小瓶云”了解更多…..
					</text>
				</view>
				<view class="contact">
					<view class="contact-line">
						<text>18801079534 （同微信）-点击可复制</text>
						<text>leo＠pbottle.com</text>
						<text>北京小瓶科技有限公司</text>
						<text>https://www.pbottle.com/</text>
						<text>北京市朝阳区高碑店吉里国际艺术区w6d01</text>
						<button type="default">一键导入手机通讯录</button>
						<button type="default">下载名片到手机</button>
					</view>
				</view>
				
			</view>
		</view>		
	</view>

</template>

<script>
	export default {
		data() {
			return {
				href: ''
			}
		},
		methods: {

		}
	}
</script>

<style>
	.status_bar {
		height: var(--status-bar-height);
		widows: 100%;
	}
	.container {
		padding: 20px;
		font-size: 14px;
		line-height: 24px;
		background-color: #37538f;
	}
	.avatar {
		width: 70%;
		height: 470rpx;
		border-radius: 20rpx;
		box-shadow: 5rpx 5rpx 30rpx rgba(0, 0, 0, 0.5);
	}
</style>
