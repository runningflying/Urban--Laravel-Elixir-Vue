<template>

    <modal :show="show"
           :modal-class="modalClass"
           :modal-style="modalStyle"
           :mask-style="maskStyle">

        <div>
            <div class="panel-heading">
                <h2 class="panel-title">Dealer Order Form for Order # {{ item.id }}</h2>
            </div>
            <div class="panel-body modal-body">
                <div class="form-container" id="v-app" v-cloak>
                    <dealer-order-form ref="dealerOrderForm"></dealer-order-form>
                </div>
            </div>
            <div class="panel-footer" style="text-align: center">
                <button type="button" class="btn btn-default" v-on:click="close">Close</button>
            </div>
        </div>

    </modal>

</template>

<script type="text/babel">
    import Modal from 'src/components/ui/Modal.vue'
    import DealerOrderForm from 'src/components/views/dealer-order-form/index.vue'
    import {mapActions} from 'vuex'

    export default {
        data() {
            return {
                modalClass: 'col-md-11 col-sm-12 col-xs-12',
                modalStyle: {
                    float: 'none',
                    padding: '0'
                },
                maskStyle: {
                    position: 'fixed'
                }
            }
        },
        mounted() {
            this.search(this.item)
        },
        components: {
            Modal,
            DealerOrderForm
        },
        props: {
            item: {
                required: true
            },
            show: {
                type: Boolean,
                default: false
            }
        },
        methods: {
            close() {
                this.$emit('close')
            },
            ...mapActions({
                loadDealerOrder: 'dealerOrderForm/loadDealerOrder',
                updateOrderSync: 'dealerOrderForm/updateOrderSync',
                uiToolsSetStateLoadForm: 'dealerOrderForm/uiTools/uiToolsSetStateLoadForm',

                searchOrders: 'dealerOrderForm/searchOrders',
                setOrderState: 'dealerOrderForm/setOrderState',
                uiToolsHideLoadForm: 'dealerOrderForm/uiTools/uiToolsHideLoadForm'
            }),
            search(order) {
                // this.$v.$touch()
                // if (this.$v.$error) return

                let self = this
                self.searchOrders({
                    payload: {
                        id: order.id
                    },
                    beforeCb() {
                        self.uiToolsSetStateLoadForm('idle')
                    },
                    successCb(response) {
                        self.load(response)
                    },
                    errorCb(response) {
                        self.$emit('data-failed', response)
                    }
                })
            },
            load(order) {
                let self = this
                this.loadDealerOrder({
                    payload: {
                        id: order.uuid
                    },
                    beforeCb() {
                        self.uiToolsSetStateLoadForm('idle')
                    },
                    successCb() {
                        self.$nextTick(function () {
                            self.updateOrderSync({merging: 'done'})
                            self.$bus.$emit('dofOrderLoaded')
                        })
                    },
                    errorCb(response) {
                        self.$parent.$emit('data-failed', response)
                    }
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>