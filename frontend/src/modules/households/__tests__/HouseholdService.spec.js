import { describe, it, expect, vi } from 'vitest';
import api from '@/plugins/axios';
import HouseholdService from '../services/HouseholdService';

vi.mock('@/plugins/axios');

describe('HouseholdService', () => {
    it('fetches households', async () => {
        const mockData = [{ id: 1, name: 'Household 1' }];
        api.get.mockResolvedValue({ data: mockData });

        const result = await HouseholdService.getHouseholds({ search: 'test' });
        expect(api.get).toHaveBeenCalledWith('/households', { params: { search: 'test' } });
        expect(result).toEqual(mockData);
    });

    it('fetches a single household', async () => {
        const mockData = { id: 1, name: 'Household 1' };
        api.get.mockResolvedValue({ data: mockData });

        const result = await HouseholdService.getHousehold(1);
        expect(api.get).toHaveBeenCalledWith('/households/1');
        expect(result).toEqual(mockData);
    });

    it('creates a household', async () => {
        const mockData = { id: 1, name: 'Household 1' };
        api.post.mockResolvedValue({ data: mockData });

        const result = await HouseholdService.createHousehold({ name: 'Household 1' });
        expect(api.post).toHaveBeenCalledWith('/households', { name: 'Household 1' });
        expect(result).toEqual(mockData);
    });

    it('updates a household', async () => {
        const mockData = { id: 1, name: 'Updated Household 1' };
        api.put.mockResolvedValue({ data: mockData });

        const result = await HouseholdService.updateHousehold(1, { name: 'Updated Household 1' });
        expect(api.put).toHaveBeenCalledWith('/households/1', { name: 'Updated Household 1' });
        expect(result).toEqual(mockData);
    });

    it('deletes a household', async () => {
        api.delete.mockResolvedValue({ data: null });

        const result = await HouseholdService.deleteHousehold(1);
        expect(api.delete).toHaveBeenCalledWith('/households/1');
        expect(result).toBeNull();
    });
});
