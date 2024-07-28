import { useWindowSize } from "@vueuse/core";
import { computed, ref } from "vue";

type Params = {
    tableId: string;
    perPage: number;
    pageSizes: number[];
    dataSource: Array<any> | Function;
    columns: Array<any>;
    withActions?: boolean;
}

export default function useDxTable(params: Params) {
    const tableId = ref<string>(params.tableId);
    const dataSource = ref<Array<any>|Function>(params.dataSource);
    const columns = ref<Array<any>>(params.columns);
    const perPage = ref<number>(params.perPage);
    const pageSizes = ref<number[]>(params.pageSizes);

    const initialized = ref(false);
    const { width } = useWindowSize();
    const isLargeDesktop = computed(() => width.value > 1280);

    const initialize = () => {
        initialized.value = true;

        if (typeof window.$ === 'undefined' || typeof window.$.fn.dxDataGrid === 'undefined') {
            return;
        }

        window.$(`#${tableId.value}`)?.dxDataGrid({
            dataSource: Array.isArray(dataSource.value) ? dataSource.value : (dataSource.value)(),
            filterRow: { visible: true },
            remoteOperations: true,
            columns: columns.value,
            columnHidingEnabled: !isLargeDesktop.value,
            paging: {
                pageSize: perPage.value
            },
            pager: {
                showPageSizeSelector: true,
                allowedPageSizes: pageSizes.value,
                showInfo: true
            }
        });

        initialized.value = false;
    }

    return {
        initialized,
        initialize,
    }
}
