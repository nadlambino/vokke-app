export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export type Gender = 'male' | 'female';

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
};

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
