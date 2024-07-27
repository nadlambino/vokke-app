import { ApiResponse, Kangaroo } from "@/types"
import { useQuery } from '@tanstack/vue-query'
import { useDebounce } from "@vueuse/core";
import { computed, ref } from "vue";


export default function useKangarooApi() {
    const search = ref('');
    const debouncedSearch = useDebounce(search, 500);

    const get = () => {
        return window.axios.get<ApiResponse<Kangaroo[]>>(route('api.kangaroos.index'), {
            params: {
                filter: {
                    search: debouncedSearch.value,
                },
            }
        })
    }

    const { data, refetch } = useQuery({
        queryKey: ['kangaroos', { debouncedSearch }],
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

    const update = async (id: number, data: Kangaroo) => {
        return await window.axios.post(route('api.kangaroos.update', id), {
            ...data,
            _method: 'PUT'
        }, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        })
    }

    const destroy = async (id: number) => {
        return await window.axios.delete(route('api.kangaroos.destroy', id))
    }

    return {
        search,
        kangaroos,
        total,
        refetch,
        create,
        update,
        destroy,
    }
}
