import { AxiosPromise, AxiosResponse } from "axios";
import { ref } from "vue";

type LoadOptions = {
    filter: Array<string|Array<string>> | undefined;
    skip: number;
    take: number;
    sort: Array<{selector: string, desc: boolean}> | undefined;
}

type Filter = {
    [key: string]: any
}

type GetterFunction = (params: object) => AxiosPromise<any>;

export default function useDxDataSource(params: { api: string, dataId: string, getter: GetterFunction }) {
    const dataId = ref<string>(params.dataId);
    const getter = ref<GetterFunction>(params.getter);

    const extractFilters = (options: LoadOptions): Filter => {
        const filter: Filter = {};

        if (!options.filter || !Array.isArray(options.filter)) return {};

        // Single filter
        if (typeof options.filter[0] === 'string') {
            const [key, _, value] = options.filter;
            filter[key] = value;
        }

        // Multiple filters
        else {
            options.filter.forEach((item) => {
                if (Array.isArray(item)) {
                    const [key, _, value] = item;
                    filter[key] = value;
                }
            });
        }

        return filter;
    }

    const extractSort = (options: LoadOptions) => {
        if (!options.sort || !Array.isArray(options.sort)) return null;

        const sort = options.sort[0];
        const { selector, desc } = sort;

        if (!selector) return null;

        return desc ? `-${selector}` : selector;
    }

    const dataSource = () => {
        if (!window.DevExpress) {
            console.warn('DevExpress is not defined. Make sure to include the DevExpress library');
            return;
        }

        return new window.DevExpress.data.CustomStore({
            key: dataId.value,
            load: async function(options: LoadOptions) {
                const filter = extractFilters(options);
                const perPage = options.take || 10;
                const page = Math.floor(options.skip / options.take) + 1;
                const sort = extractSort(options);
                const params = { page, filter, per_page: perPage, sort };

                return await (getter.value)(params).then((response: AxiosResponse) => {
                    const { data } = response;
                    return { data: data.data, totalCount: data.metadata.total_count };
                });
            },
        })
    };

    return dataSource
}