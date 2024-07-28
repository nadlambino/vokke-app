import { ApiResponse, Kangaroo } from "@/types"

export default function useKangarooApi() {
    const get = (params: object) => {
        return window.axios.get<ApiResponse<Kangaroo[]>>(route('api.kangaroos.index'), {
            params
        })
    }

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
        get,
        create,
        update,
        destroy,
    }
}
