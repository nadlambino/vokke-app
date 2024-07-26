import { Gender } from "@/types";

export interface Kangaroo {
    id?: number,
    name?: string,
    nickname?: string,
    weight?: number,
    height?: number,
    gender?: Gender,
    color?: string,
    friendliness?: Friendliness,
    birthday?: string | Date,
}

export type Friendliness = 'friendly' | 'independent';
