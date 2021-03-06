<template>

    <div class="search-form form-horizontal">
        <ul class="list-group list-group-horizontal list-tools-form">
            <template v-for="search in currentSearches">
                <form-search-date v-if="search.id === 'date_created'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD HH:mm:ss'">
                </form-search-date>
                <form-search-select v-if="search.id === 'status_id'"
                                      v-bind:title="'title'"
                                      v-bind:datas="saleStatuses"
                                      v-bind:item="search">
                </form-search-select>
                <form-search-text v-if="search.id === 'sale_id'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-text v-if="search.id === 'order_id'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-date v-if="search.id === 'order_date'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD'">
                </form-search-date>
                <form-search-date v-if="search.id === 'date_submitted'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD'">
                </form-search-date>
                <form-search-select v-if="search.id === 'serial_numbers_values'"
                                    :label="'serialNumber'"
                                    :trackBy="'serialNumber'"
                                    v-bind:title="'name'"
                                    v-bind:datas="serialNumbersValues"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-text v-if="search.id === 'retail'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-text v-if="search.id === 'deposit_received'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-text v-if="search.id === 'sales_tax'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-select v-if="search.id === 'order_type'"
                                    :label="'title'"
                                    v-bind:title="'name'"
                                    v-bind:datas="orderTypes"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-select v-if="search.id === 'payment_type'"
                                    :label="'title'"
                                    v-bind:title="'name'"
                                    v-bind:datas="paymentTypes"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-select v-if="search.id === 'dealer'"
                                    :label="'businessName'"
                                    v-bind:title="'businessName'"
                                    v-bind:datas="dealers"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-async-text v-if="search.id === 'sales_person'"
                                        :is-loading="salesPersonNamesLoading"
                                        :autocomplete-values="salesPersonNames"
                                        @fetch-autocomplete="fetchSalesPerson"
                                        v-bind:item="search">
                </form-search-async-text>
                <form-search-async-text v-if="search.id === 'customer'"
                                        :is-loading="customerNamesLoading"
                                        :autocomplete-values="customerNames"
                                        @fetch-autocomplete="fetchCustomers"
                                        v-bind:item="search">
                </form-search-async-text>
                <form-search-text v-if="search.id === 'invoice_id'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-async-text v-if="search.id === 'location'"
                                        :is-loading="locationNamesLoading"
                                        :autocomplete-values="locationNames"
                                        :block-width="'320px'"
                                        @fetch-autocomplete="fetchLocations"
                                        v-bind:item="search">
                </form-search-async-text>
                <form-search-text v-if="search.id === 'delivery_id'"
                                  v-bind:item="search">
                </form-search-text>
                <form-search-select v-if="search.id === 'delivery_status'"
                                    dataType="objects"
                                    :label="'name'"
                                    v-bind:title="'title'"
                                    v-bind:datas="deliveryStatuses"
                                    v-bind:item="search">
                </form-search-select>
            </template>
        </ul>
    </div>

</template>

<script type="text/babel">
    import baseSearchForm from 'src/components/views/_base/ListItems/filters/SearchForm.vue'
    import FormSearchDate from 'src/components/views/_base/ListItems/search-forms/Date.vue'
    import FormSearchSelect from 'src/components/views/_base/ListItems/search-forms/Select.vue'
    import FormSearchText from 'src/components/views/_base/ListItems/search-forms/TextInput.vue'
    import FormSearchAsyncText from 'src/components/views/_base/ListItems/search-forms/AsyncInput.vue'

    import apiSales from 'src/api/sales'
    import apiBuildings from 'src/api/buildings'
    import apiOrders from 'src/api/orders'
    import apiDealers from 'src/api/dealers'
    import apiReferences from 'src/api/order-references'
    import apiLocations from 'src/api/locations'
    import apiDeliveries from 'src/api/deliveries'

    export default {
        extends: baseSearchForm,
        data() {
            return {
                saleStatuses: {},
                serialNumbersValues: [],
                orderTypes: [],
                paymentTypes: [],
                dealers: [],
                salesPersonNames: [],
                salesPersonNamesLoading: false,
                customerNames: [],
                customerNamesLoading: false,
                locationNamesLoading: false,
                locationNames: [],
                deliveryStatuses: {}
            }
        },
        components: {
            FormSearchDate,
            FormSearchSelect,
            FormSearchText,
            FormSearchAsyncText
        },
        methods: {
            syncSearches() {},
            fetchData() {
                const datas = [
                    apiSales.statuses({}),
                    apiBuildings.get({
                        query: {
                            per_page: 99999,
                            fields: ['id', 'serial_number', 'order_id'],
                            include: ['order.sale'],
                            leftJoinRelation: ['order.sale'],
                            order_by: ['serial_number asc'],
                            where: {
                                serial_number: {
                                    notnull: true
                                },
                                order_id: {
                                    notnull: true
                                },
                                'order.status_id': {
                                    '<>': 'draft'
                                },
                                'order.sale.status_id': {
                                    '<>': 'draft'
                                }
                            }
                        }
                    }),
                    apiOrders.orderTypes(),
                    apiDealers.get({
                        params: {
                            fields: ['id', 'business_name'],
                            per_page: 9999
                        }
                    }),
                    apiDeliveries.statuses({}),
                    apiOrders.paymentTypes()
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.saleStatuses = response[0].data
                        this.serialNumbersValues = response[1].data.data
                        this.orderTypes = response[2].data
                        this.dealers = response[3].data.data
                        this.deliveryStatuses = response[4].data
                        this.paymentTypes = response[5].data
                        this.$emit('data-ready')
                        return response
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            },
            fetchSalesPerson(searchKeyword) {
                this.salesPersonNamesLoading = true
                let query = {
                    per_page: 99999
                }
                let searchQuery = '%' + searchKeyword + '%'
                _.set(query, ['where', 'sales_person', 'like'], searchQuery)
                const namesData = apiOrders.get({
                    query
                })

                return Promise.resolve(namesData)
                    .then(response => {
                        let salesPersonNamesStructured = []
                        const dataNotStructured = response.body.data
                        for (let item of dataNotStructured) {
                            if (_.findIndex(salesPersonNamesStructured, { 'name': item.salesPerson }) < 0) {
                                salesPersonNamesStructured.push({name: item.salesPerson})
                            }
                        }
                        this.salesPersonNamesLoading = false
                        this.salesPersonNames = salesPersonNamesStructured
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            },
            fetchCustomers(searchKeyword) {
                this.customerNamesLoading = true
                let query = {
                    per_page: 99999
                }
                let searchQuery = '%' + searchKeyword + '%'
                _.set(query, ['where', 'first_name', 'like'], searchQuery)
                _.set(query, ['where', 'or', 'last_name', 'like'], searchQuery)
                const namesData = apiReferences.get({
                    query
                })

                return Promise.resolve(namesData)
                    .then(response => {
                        let customerNamesStructured = []
                        const dataNotStructured = response.body.data
                        for (let item of dataNotStructured) {
                            if (item.firstName.toLowerCase().indexOf(searchKeyword.toLowerCase()) >= 0) {
                                if (_.findIndex(customerNamesStructured, { 'name': item.firstName }) < 0) {
                                    customerNamesStructured.push({name: item.firstName})
                                }
                            }
                            if (item.lastName.toLowerCase().indexOf(searchKeyword.toLowerCase()) >= 0) {
                                if (_.findIndex(customerNamesStructured, { 'name': item.lastName }) < 0) {
                                    customerNamesStructured.push({name: item.lastName})
                                }
                            }
                        }
                        this.customerNamesLoading = false
                        this.customerNames = customerNamesStructured
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            },
            fetchLocations(searchKeyword) {
                this.locationNamesLoading = true
                let query = {
                    per_page: 99999
                }
                let searchQuery = '%' + searchKeyword + '%'
                _.set(query, ['where', 'name', 'like'], searchQuery)
                const namesData = apiLocations.get({
                    query
                })

                return Promise.resolve(namesData)
                    .then(response => {
                        let customerNamesStructured = []
                        const dataNotStructured = response.body.data
                        for (let item of dataNotStructured) {
                            customerNamesStructured.push({name: item.name})
                        }
                        this.locationNamesLoading = false
                        this.locationNames = customerNamesStructured
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            }
        }
    }
</script>