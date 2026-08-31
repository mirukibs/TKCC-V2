import { describe, it, expect, vi, beforeEach } from 'vitest';
import ZoneService from '../services/ZoneService';
import api from '@/plugins/axios';

vi.mock('@/plugins/axios', () => ({
  default: {
    get: vi.fn(),
    post: vi.fn(),
    put: vi.fn(),
    delete: vi.fn(),
  }
}));

describe('ZoneService.js', () => {
  beforeEach(() => {
    vi.resetAllMocks();
  });

  it('fetches all zones', async () => {
    const mockResponse = { data: { data: [{ id: 1, name: 'Zone A' }] } };
    api.get.mockResolvedValue(mockResponse);
    const result = await ZoneService.getAll();
    expect(api.get).toHaveBeenCalledWith('/zones');
    expect(result).toEqual(mockResponse);
  });

  it('fetches a zone by id', async () => {
    const mockResponse = { data: { data: { id: 1, name: 'Zone A' } } };
    api.get.mockResolvedValue(mockResponse);
    const result = await ZoneService.getById(1);
    expect(api.get).toHaveBeenCalledWith('/zones/1');
    expect(result).toEqual(mockResponse);
  });

  it('creates a zone', async () => {
    const mockResponse = { data: { id: 1 } };
    api.post.mockResolvedValue(mockResponse);
    const result = await ZoneService.create({ name: 'Zone A' });
    expect(api.post).toHaveBeenCalledWith('/zones', { name: 'Zone A' });
    expect(result).toEqual(mockResponse);
  });

  it('updates a zone', async () => {
    const mockResponse = { data: { id: 1 } };
    api.put.mockResolvedValue(mockResponse);
    const result = await ZoneService.update(1, { name: 'Zone B' });
    expect(api.put).toHaveBeenCalledWith('/zones/1', { name: 'Zone B' });
    expect(result).toEqual(mockResponse);
  });

  it('deletes a zone', async () => {
    const mockResponse = { data: null };
    api.delete.mockResolvedValue(mockResponse);
    const result = await ZoneService.delete(1);
    expect(api.delete).toHaveBeenCalledWith('/zones/1');
    expect(result).toEqual(mockResponse);
  });
});
