import { Kangaroo } from "@/types"

export default function useKangarooApi() {
    const create = async (data: Kangaroo) => {
        return await window.axios.post(route('api.kangaroos.store'), data, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        })
    }

    return {
        create
    }
}
