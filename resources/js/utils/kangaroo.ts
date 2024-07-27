import { ApiResponse, Kangaroo } from "@/types"
import { useQuery } from '@tanstack/vue-query'
import { useDebounce } from "@vueuse/core";
import { computed, ref } from "vue";


export default function useKangarooApi() {
    const search = ref('');
    const debouncedSearch = useDebounce(search, 500);
    const page = ref(1);
    const perPage = ref(10);

    const get = () => {
        return window.axios.get<ApiResponse<Kangaroo[]>>(route('api.kangaroos.index'), {
            params: {
                filter: {
                    search: debouncedSearch.value,
                },
                page: page.value,
                perPage: perPage.value,
            }
        })
    }

    const { isPending, isFetching, isError, data, error } = useQuery({
        queryKey: ['kangaroos', { debouncedSearch, page, perPage }],
        queryFn: get,
    });

    const kangaroos = computed<Kangaroo[]>(() => data.value?.data.data || [])
    const total = computed<number>(() => data.value?.data.metadata.total_count || 0)

    const create = async (data: Kangaroo) => {
        return await window.axios.post(route('api.kangaroos.store'), data, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        })
    }

    return {
        search,
        perPage,
        page,
        kangaroos,
        total,
        create
    }
}
