const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  transpileDependencies: true
})


module.exports = {
  publicPath:'./',  // 执行 npm run build 统一配置路径
  pwa: {
    name: 'ImgCloud图床',
    themeColor: '#2674df',
    msTileColor: '#2674df',
    skipWaiting: true,
    clientsClaim: true,
    manifestOptions: {
      start_url: '.',
      background_color: '#4c89fe'
    },
    workboxPluginMode: 'GenerateSW',
    workboxOptions: {
    }
  }
}
