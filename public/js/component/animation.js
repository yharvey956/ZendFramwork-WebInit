var elTransition = '0.2s height ease-in-out, 0.2s padding-top ease-in-out, 0.2s padding-bottom ease-in-out'
var Transition = {
    'before-enter' :function(el) {
        el.style.transition = elTransition
        if (!el.dataset) el.dataset = {}

        el.dataset.oldPaddingTop = el.style.paddingTop
        el.dataset.oldPaddingBottom = el.style.paddingBottom

        el.style.height = 0
        el.style.paddingTop = 0
        el.style.paddingBottom = 0
    },

    'enter' :function(el){
        el.dataset.oldOverflow = el.style.overflow
        if (el.scrollHeight !== 0) {
            el.style.height = el.scrollHeight + 'px'
            el.style.paddingTop = el.dataset.oldPaddingTop
            el.style.paddingBottom = el.dataset.oldPaddingBottom
        } else {
            el.style.height = ''
            el.style.paddingTop = el.dataset.oldPaddingTop
            el.style.paddingBottom = el.dataset.oldPaddingBottom
        }

        el.style.overflow = 'hidden'
    },

    'after-enter' :function(el){
        el.style.transition = ''
        el.style.height = ''
        el.style.overflow = el.dataset.oldOverflow
    },

    'before-leave'  :function(el){
        if (!el.dataset) el.dataset = {}
        el.dataset.oldPaddingTop = el.style.paddingTop
        el.dataset.oldPaddingBottom = el.style.paddingBottom
        el.dataset.oldOverflow = el.style.overflow

        el.style.height = el.scrollHeight + 'px'
        el.style.overflow = 'hidden'
    },

    'leave' :function(el){
        if (el.scrollHeight !== 0) {
            el.style.transition = elTransition
            el.style.height = 0
            el.style.paddingTop = 0
            el.style.paddingBottom = 0
        }
    },

    'after-leave' :function(el) {
        el.style.transition = ''
        el.style.height = ''
        el.style.overflow = el.dataset.oldOverflow
        el.style.paddingTop = el.dataset.oldPaddingTop
        el.style.paddingBottom = el.dataset.oldPaddingBottom
    }
}

Vue.component('collapse-transition', {
    functional: true,
    // 为了弥补缺少的实例
    // 提供第二个参数作为上下文
    render: function (createElement, context) {
        var data = {
            on: Transition
        }
        return createElement( 'transition', data, context.children)
    },
})
