'use client'

import { useState, useEffect } from 'react'
import { useRouter } from 'next/navigation'
import { supabase } from '@/lib/supabase/client'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { useToast } from '@/hooks/use-toast'
import { ArrowLeft, Package, Plus } from 'lucide-react'
import Link from 'next/link'

interface SavedAddress {
  id: string
  address_line1: string
  address_line2?: string
  city: string
  state: string
  zip_code: string
  is_default: boolean
}

export default function NewCollectionRequest() {
  const router = useRouter()
  const { toast } = useToast()
  const [loading, setLoading] = useState(false)
  const [userId, setUserId] = useState<string>('')
  const [savedAddresses, setSavedAddresses] = useState<SavedAddress[]>([])
  const [selectedAddressId, setSelectedAddressId] = useState<string>('new')
  const [formData, setFormData] = useState({
    waste_type: '',
    quantity: '',
    pickup_date: '',
    pickup_time: '',
    address: '',
    description: '',
    priority: 'medium',
    special_instructions: '',
  })

  useEffect(() => {
    checkAuth()
  }, [])

  async function checkAuth() {
    const { data: { user } } = await supabase.auth.getUser()
    if (!user) {
      router.push('/login')
      return
    }
    setUserId(user.id)
    await loadAddresses(user.id)
  }

  async function loadAddresses(userId: string) {
    try {
      const { data, error } = await supabase
        .from('user_addresses')
        .select('*')
        .eq('user_id', userId)
        .order('is_default', { ascending: false })

      if (error) throw error

      setSavedAddresses(data || [])
      
      // Auto-select default address if exists
      const defaultAddress = data?.find(addr => addr.is_default)
      if (defaultAddress) {
        setSelectedAddressId(defaultAddress.id)
        handleAddressSelect(defaultAddress.id, data || [])
      }
    } catch (error) {
      console.error('Error loading addresses:', error)
    }
  }

  function handleAddressSelect(addressId: string, addresses: SavedAddress[] = savedAddresses) {
    setSelectedAddressId(addressId)
    
    if (addressId === 'new') {
      setFormData({ ...formData, address: '' })
    } else {
      const selected = addresses.find(addr => addr.id === addressId)
      if (selected) {
        const fullAddress = [
          selected.address_line1,
          selected.address_line2,
          `${selected.city}, ${selected.state} ${selected.zip_code}`
        ].filter(Boolean).join(', ')
        
        setFormData({ ...formData, address: fullAddress })
      }
    }
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)

    try {
      const { error } = await supabase
        .from('waste_requests')
        .insert({
          user_id: userId,
          waste_type: formData.waste_type,
          quantity: parseFloat(formData.quantity),
          pickup_date: formData.pickup_date,
          pickup_time: formData.pickup_time || null,
          address: formData.address,
          description: formData.description || null,
          priority: formData.priority,
          special_instructions: formData.special_instructions || null,
          status: 'pending',
        })

      if (error) throw error

      toast({
        title: 'Success!',
        description: 'Your collection request has been submitted',
      })

      router.push('/dashboard')
    } catch (error) {
      console.error('Error:', error)
      toast({
        title: 'Error',
        description: error instanceof Error ? error.message : 'Failed to submit request',
        variant: 'destructive',
      })
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-green-50 to-blue-50">
      {/* Header */}
      <header className="bg-white border-b">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
          <div className="flex items-center gap-3">
            <Link href="/dashboard">
              <Button variant="ghost" size="sm" className="gap-2">
                <ArrowLeft className="h-4 w-4" />
                Back
              </Button>
            </Link>
            <div className="flex items-center gap-2">
              <Package className="h-6 w-6 text-green-600" />
              <h1 className="text-xl font-bold text-gray-900">New Collection Request</h1>
            </div>
          </div>
        </div>
      </header>

      {/* Form */}
      <main className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <Card>
          <CardHeader>
            <CardTitle>Submit Waste Collection Request</CardTitle>
            <CardDescription>
              Fill in the details below to request a waste collection
            </CardDescription>
          </CardHeader>
          <CardContent>
            <form onSubmit={handleSubmit} className="space-y-6">
              {/* Waste Type */}
              <div className="space-y-2">
                <Label htmlFor="waste_type">Waste Type *</Label>
                <Select
                  value={formData.waste_type}
                  onValueChange={(value) => setFormData({ ...formData, waste_type: value })}
                  required
                >
                  <SelectTrigger>
                    <SelectValue placeholder="Select waste type" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="Organic">Organic</SelectItem>
                    <SelectItem value="Recyclable">Recyclable</SelectItem>
                    <SelectItem value="Electronic">Electronic</SelectItem>
                    <SelectItem value="Hazardous">Hazardous</SelectItem>
                    <SelectItem value="General">General</SelectItem>
                  </SelectContent>
                </Select>
              </div>

              {/* Quantity */}
              <div className="space-y-2">
                <Label htmlFor="quantity">Quantity (kg) *</Label>
                <Input
                  id="quantity"
                  type="number"
                  step="0.1"
                  min="0.1"
                  placeholder="e.g., 10.5"
                  value={formData.quantity}
                  onChange={(e) => setFormData({ ...formData, quantity: e.target.value })}
                  required
                />
              </div>

              {/* Pickup Date & Time */}
              <div className="grid md:grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="pickup_date">Pickup Date *</Label>
                  <Input
                    id="pickup_date"
                    type="date"
                    min={new Date().toISOString().split('T')[0]}
                    value={formData.pickup_date}
                    onChange={(e) => setFormData({ ...formData, pickup_date: e.target.value })}
                    required
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="pickup_time">Pickup Time (Optional)</Label>
                  <Input
                    id="pickup_time"
                    type="time"
                    value={formData.pickup_time}
                    onChange={(e) => setFormData({ ...formData, pickup_time: e.target.value })}
                  />
                </div>
              </div>

              {/* Priority */}
              <div className="space-y-2">
                <Label htmlFor="priority">Priority *</Label>
                <Select
                  value={formData.priority}
                  onValueChange={(value) => setFormData({ ...formData, priority: value })}
                  required
                >
                  <SelectTrigger>
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="low">Low</SelectItem>
                    <SelectItem value="medium">Medium</SelectItem>
                    <SelectItem value="high">High</SelectItem>
                  </SelectContent>
                </Select>
              </div>

              {/* Address Selection */}
              <div className="space-y-2">
                <Label htmlFor="address_select">Collection Address *</Label>
                <Select value={selectedAddressId} onValueChange={handleAddressSelect}>
                  <SelectTrigger>
                    <SelectValue placeholder="Select an address" />
                  </SelectTrigger>
                  <SelectContent>
                    {savedAddresses.map((addr) => (
                      <SelectItem key={addr.id} value={addr.id}>
                        <div className="flex items-center gap-2">
                          {addr.is_default && <span className="text-green-600">★</span>}
                          {addr.address_line1}, {addr.city}
                        </div>
                      </SelectItem>
                    ))}
                    <SelectItem value="new">
                      <div className="flex items-center gap-2">
                        <Plus className="h-4 w-4" />
                        Enter new address
                      </div>
                    </SelectItem>
                  </SelectContent>
                </Select>
              </div>

              {/* Address Input (shown when "new" is selected) */}
              {selectedAddressId === 'new' && (
                <div className="space-y-2">
                  <Label htmlFor="address">Enter Address *</Label>
                  <Textarea
                    id="address"
                    placeholder="Enter your full address"
                    rows={3}
                    value={formData.address}
                    onChange={(e) => setFormData({ ...formData, address: e.target.value })}
                    required
                  />
                  <p className="text-xs text-gray-500">
                    Tip: Save this address in{' '}
                    <Link href="/addresses" className="text-primary hover:underline">
                      Address Management
                    </Link>{' '}
                    for future use
                  </p>
                </div>
              )}

              {/* Selected Address Display */}
              {selectedAddressId !== 'new' && formData.address && (
                <div className="p-3 bg-gray-50 rounded-lg border">
                  <p className="text-sm font-medium text-gray-700">Selected Address:</p>
                  <p className="text-sm text-gray-600 mt-1">{formData.address}</p>
                </div>
              )}

              {/* Description */}
              <div className="space-y-2">
                <Label htmlFor="description">Description (Optional)</Label>
                <Textarea
                  id="description"
                  placeholder="Additional details about the waste"
                  rows={3}
                  value={formData.description}
                  onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                />
              </div>

              {/* Special Instructions */}
              <div className="space-y-2">
                <Label htmlFor="special_instructions">Special Instructions (Optional)</Label>
                <Textarea
                  id="special_instructions"
                  placeholder="Any special handling requirements"
                  rows={2}
                  value={formData.special_instructions}
                  onChange={(e) => setFormData({ ...formData, special_instructions: e.target.value })}
                />
              </div>

              {/* Submit Buttons */}
              <div className="flex gap-4 pt-4">
                <Button type="submit" disabled={loading} className="flex-1">
                  {loading ? 'Submitting...' : 'Submit Request'}
                </Button>
                <Link href="/dashboard" className="flex-1">
                  <Button type="button" variant="outline" className="w-full">
                    Cancel
                  </Button>
                </Link>
              </div>
            </form>
          </CardContent>
        </Card>
      </main>
    </div>
  )
}
