//路由
var routers = [{
    path: '', name: "后台", component: gmallComponent("/admin/index/home")
}, {
    path: "/admin/index/home", name: "后台主页", component: gmallComponent("/admin/index/home")
}];
//生成前端路由
navArr.forEach(function (item) {
    if (item.childnode && item.childnode.length > 0) {
        item.childnode.forEach(function (target) {
            routers.push({
                path: target.url,
                name: target.name,
                component: gmallComponent(target.url)
            });
        }.bind(this));
    } else {
        routers.push({
            path: item.url,
            name: item.name,
            component: gmallComponent(item.url)
        });
    }
});
var router = new VueRouter({routes: routers});
Vue.use(VueRouter);
var rootVm = new Vue({
    router: router,
    el: "#main",
    data: {
        msg: "hello world",
        //当前选中的菜单
        currentNav: "",
        webNavList: _.map(navArr, function (item) {
            item.show = false;
            return item;
        }),
    },
    created: function () {
        //查看进入当前页面的时候路由是否为首页，否则将当前选择的路由菜单设置为对应
        var currentRoute = window.location.href.split("#").pop();
        if (currentRoute != "" && currentRoute != "/" && currentRoute != "/admin/index/home") {
            this.currentNav = {
                attrid: currentRoute.split("/").pop(),
                url: currentRoute
            };
        }
    },
    methods: {
        showNav: function (value) {
            value.show = !value.show;
            this.webNavList = _.map(this.webNavList, function (item) {
                item.show = item != value ? false : item.show;
                return item;
            });
        },
    }
});
