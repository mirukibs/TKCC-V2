import { describe, it, expect, vi, beforeEach } from 'vitest';
import MemberService from '@/modules/members/services/MemberService';
import api from '@/plugins/axios';

// Mock the axios instance
vi.mock('@/plugins/axios', () => {
    return {
        default: {
            get: vi.fn(),
            post: vi.fn(),
        }
    }
});

describe('MemberService', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('should fetch all members', async () => {
        const mockResponse = { data: [{ id: 1, full_name: 'John Doe' }] };
        api.get.mockResolvedValueOnce(mockResponse);

        const result = await MemberService.getMembers();

        expect(api.get).toHaveBeenCalledWith('/members');
        expect(api.get).toHaveBeenCalledTimes(1);
        expect(result).toEqual(mockResponse.data);
    });

    it('should fetch a single member by id', async () => {
        const mockResponse = { data: { id: 1, full_name: 'John Doe' } };
        api.get.mockResolvedValueOnce(mockResponse);

        const result = await MemberService.getMember(1);

        expect(api.get).toHaveBeenCalledWith('/members/1');
        expect(api.get).toHaveBeenCalledTimes(1);
        expect(result).toEqual(mockResponse.data);
    });

    it('should create a new member', async () => {
        const payload = { first_name: 'Jane', last_name: 'Doe' };
        const mockResponse = { data: { id: 2, first_name: 'Jane', last_name: 'Doe' } };
        api.post.mockResolvedValueOnce(mockResponse);

        const result = await MemberService.createMember(payload);

        expect(api.post).toHaveBeenCalledWith('/members', payload);
        expect(api.post).toHaveBeenCalledTimes(1);
        expect(result).toEqual(mockResponse.data);
    });
});
