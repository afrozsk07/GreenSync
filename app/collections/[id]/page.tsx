'use client'

import { useState, useEffect } from 'react'
import { useRouter, useParams } from 'next/navigation'
import { supabase } from '@/lib/supabase/client'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { useToast } from '@/hooks/use-toast'
import Link from 'next/link'
import { ArrowLeft, Package, Clock, MapPin, AlertCircle, Trash2 } from 'lucide-react'

interface WasteRequest {
  id: string
  waste_type: string
  quantity: number
  pickup_date: string
  pickup_time?: string
  address: string
  status: string
  priority: string
  description?: string
  special_instructions?: string
  created_at: string
  updated_at: string
  collections?: Array<{
    id: string
    status: string
    actual_pickup_time?: string
    completion_time?: string
    vehicles?: { vehicle_number: string; vehicle_type: string }
    drivers?: { name: string; phone: string }
  }>
}

export default function RequestDetailsPage() {
  const router = useRouter()
  const params = useParams()
  const { toast } = useToast()
  const [loading, setLoading] = useState(true)
  const [request, setRequest] = useState<WasteRequest | null>(null)
  const [cancelling, setCancelling] = useState(false)

  useEffect(() => {
    loadRequest()
  }, [])

  async function loadRequest() {
    try {
      const { data: { user } } = await supabase.auth.getUser()
      if (!user) {
        router.push('/login')
        return
      }

      const { data, error } = await supabase
        .from('waste_requests')
        .select('*')
        .eq('id', params.id)
        .eq('user_id', user.id)
        .single()

      if (error) throw error

      // Fetch collection info separately
      if (data) {
        const { data: collectionData, error: collectionError } = await supabase
          .from('collections')
          .select(`
            id,
            status,
            actual_pickup_time,
            completion_time,
            vehicle_id,
            driver_id,
            vehicles:vehicle_id (
              vehicle_number,
              vehicle_type
            ),
            drivers:driver_id (
              name,
              phone
            )
          `)
          .eq('waste_request_id', data.id)
          .eq('user_id', user.id)
          .maybeSingle()

        if (collectionError) {
          console.error('Error fetching collection:', collectionError)
        }

        setRequest({
          ...data,
          collections: collectionData ? [collectionData] : []
        })
      } else {
        setRequest(data)
      }
    } catch (error) {
      console.error('Error loading request:', error)
      toast({
        title: 'Error',
        description: 'Failed to load request details',
        variant: 'destructive',
      })
      router.push('/collections')
    } finally {
      setLoading(false)
    }
  }

  async function handleCancel() {
    if (!request || !confirm('Are you sure you want to cancel this request?')) {
      return
    }

    setCancelling(true)
    try {
      const { error } = await supabase
        .from('waste_requests')
        .update({ status: 'cancelled' })
        .eq('id', request.id)

      if (error) throw error

      toast({
        title: 'Success',
        description: 'Request cancelled successfully',
      })

      router.push('/collections')
    } catch (error) {
      console.error('Error cancelling request:', error)
      toast({
        title: 'Error',
        description: 'Failed to cancel request',
        variant: 'destructive',
      })
    } finally {
      setCancelling(false)
    }
  }

  function getStatusColor(status: string) {
    switch (status) {
      case 'pending': return 'bg-yellow-100 text-yellow-800'
      case 'approved': return 'bg-green-100 text-green-800'
      case 'rejected': return 'bg-red-100 text-red-800'
      case 'cancelled': return 'bg-gray-100 text-gray-800'
      default: return 'bg-gray-100 text-gray-800'
    }
  }

  function getPriorityColor(priority: string) {
    switch (priority) {
      case 'high': return 'bg-red-100 text-red-800'
      case 'medium': return 'bg-yellow-100 text-yellow-800'
      case 'low': return 'bg-blue-100 text-blue-800'
      default: return 'bg-gray-100 text-gray-800'
    }
  }

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <p className="text-lg">Loading...</p>
      </div>
    )
  }

  if (!request) {
    return null
  }

  const canCancel = request.status === 'pending'

  return (
    <div className="min-h-screen bg-gradient-to-br from-green-50 to-blue-50">
      {/* Header */}
      <header className="bg-white border-b">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
          <div className="flex items-center gap-3">
            <Link href="/collections">
              <Button variant="ghost" size="sm" className="gap-2">
                <ArrowLeft className="h-4 w-4" />
                Back to Requests
              </Button>
            </Link>
            <div className="flex items-center gap-2">
              <Package className="h-6 w-6 text-green-600" />
              <h1 className="text-xl font-bold text-gray-900">Request Details</h1>
            </div>
          </div>
        </div>
      </header>

      {/* Main Content */}
      <main className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div className="space-y-6">
          {/* Status Card */}
          <Card>
            <CardHeader>
              <div className="flex items-center justify-between">
                <div>
                  <CardTitle className="text-2xl">{request.waste_type}</CardTitle>
                  <CardDescription>Request ID: {request.id.slice(0, 8)}</CardDescription>
                </div>
                <div className="flex gap-2">
                  <Badge className={getStatusColor(request.status)}>
                    {request.status}
                  </Badge>
                  <Badge className={getPriorityColor(request.priority)}>
                    {request.priority} priority
                  </Badge>
                </div>
              </div>
            </CardHeader>
          </Card>

          {/* Details Card */}
          <Card>
            <CardHeader>
              <CardTitle>Collection Details</CardTitle>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="grid md:grid-cols-2 gap-6">
                <div className="flex items-start gap-3">
                  <Package className="h-5 w-5 text-gray-400 mt-0.5" />
                  <div>
                    <p className="text-sm font-medium text-gray-500">Quantity</p>
                    <p className="text-lg font-semibold">{request.quantity} kg</p>
                  </div>
                </div>

                <div className="flex items-start gap-3">
                  <Clock className="h-5 w-5 text-gray-400 mt-0.5" />
                  <div>
                    <p className="text-sm font-medium text-gray-500">Pickup Date & Time</p>
                    <p className="text-lg font-semibold">
                      {new Date(request.pickup_date).toLocaleDateString()}
                    </p>
                    {request.pickup_time && (
                      <p className="text-sm text-gray-600">{request.pickup_time}</p>
                    )}
                  </div>
                </div>
              </div>

              <div className="flex items-start gap-3 pt-4 border-t">
                <MapPin className="h-5 w-5 text-gray-400 mt-0.5" />
                <div className="flex-1">
                  <p className="text-sm font-medium text-gray-500 mb-1">Collection Address</p>
                  <p className="text-gray-900">{request.address}</p>
                </div>
              </div>

              {request.description && (
                <div className="flex items-start gap-3 pt-4 border-t">
                  <AlertCircle className="h-5 w-5 text-gray-400 mt-0.5" />
                  <div className="flex-1">
                    <p className="text-sm font-medium text-gray-500 mb-1">Description</p>
                    <p className="text-gray-900">{request.description}</p>
                  </div>
                </div>
              )}

              {request.special_instructions && (
                <div className="flex items-start gap-3 pt-4 border-t">
                  <AlertCircle className="h-5 w-5 text-gray-400 mt-0.5" />
                  <div className="flex-1">
                    <p className="text-sm font-medium text-gray-500 mb-1">Special Instructions</p>
                    <p className="text-gray-900">{request.special_instructions}</p>
                  </div>
                </div>
              )}
            </CardContent>
          </Card>

          {/* Timeline Card */}
          <Card>
            <CardHeader>
              <CardTitle>Timeline</CardTitle>
            </CardHeader>
            <CardContent className="space-y-3">
              <div className="flex items-center gap-3">
                <div className="h-2 w-2 rounded-full bg-green-500"></div>
                <div>
                  <p className="text-sm font-medium">Request Created</p>
                  <p className="text-xs text-gray-500">
                    {new Date(request.created_at).toLocaleString()}
                  </p>
                </div>
              </div>

              {request.status === 'approved' && (
                <div className="flex items-center gap-3">
                  <div className="h-2 w-2 rounded-full bg-green-500"></div>
                  <div>
                    <p className="text-sm font-medium">Request Approved</p>
                    <p className="text-xs text-gray-500">
                      {new Date(request.updated_at).toLocaleString()}
                    </p>
                  </div>
                </div>
              )}

              {request.status === 'rejected' && (
                <div className="flex items-center gap-3">
                  <div className="h-2 w-2 rounded-full bg-red-500"></div>
                  <div>
                    <p className="text-sm font-medium">Request Rejected</p>
                    <p className="text-xs text-gray-500">
                      {new Date(request.updated_at).toLocaleString()}
                    </p>
                  </div>
                </div>
              )}

              {request.status === 'cancelled' && (
                <div className="flex items-center gap-3">
                  <div className="h-2 w-2 rounded-full bg-gray-500"></div>
                  <div>
                    <p className="text-sm font-medium">Request Cancelled</p>
                    <p className="text-xs text-gray-500">
                      {new Date(request.updated_at).toLocaleString()}
                    </p>
                  </div>
                </div>
              )}

              {request.status === 'pending' && (
                <div className="flex items-center gap-3">
                  <div className="h-2 w-2 rounded-full bg-yellow-500 animate-pulse"></div>
                  <div>
                    <p className="text-sm font-medium">Awaiting Admin Approval</p>
                    <p className="text-xs text-gray-500">Pending review</p>
                  </div>
                </div>
              )}
            </CardContent>
          </Card>

          {/* Expected Pickup Time */}
          <Card>
            <CardHeader>
              <CardTitle>Pickup Schedule</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="flex items-start gap-3">
                <Clock className="h-5 w-5 text-gray-400 mt-0.5" />
                <div>
                  <p className="text-sm font-medium text-gray-500">Expected Pickup Time</p>
                  {request.pickup_date && request.pickup_time ? (
                    <p className="text-lg font-semibold">
                      {new Date(request.pickup_date + 'T' + request.pickup_time).toLocaleString()}
                    </p>
                  ) : request.pickup_date ? (
                    <p className="text-lg font-semibold">
                      {new Date(request.pickup_date).toLocaleDateString()} - Time TBD
                    </p>
                  ) : (
                    <p className="text-lg font-semibold text-gray-500">TBD</p>
                  )}
                </div>
              </div>
            </CardContent>
          </Card>

          {/* Collection Assignment Info */}
          {request.collections && request.collections.length > 0 && request.collections[0] && (
            <Card>
              <CardHeader>
                <CardTitle>Collection Assignment</CardTitle>
                <CardDescription>Your collection has been assigned</CardDescription>
              </CardHeader>
              <CardContent className="space-y-4">
                <div className="grid md:grid-cols-2 gap-6">
                  {request.collections[0].vehicles && (
                    <div className="flex items-start gap-3">
                      <Package className="h-5 w-5 text-green-600 mt-0.5" />
                      <div>
                        <p className="text-sm font-medium text-gray-500">Assigned Vehicle</p>
                        <p className="text-lg font-semibold">
                          {request.collections[0].vehicles.vehicle_number}
                        </p>
                        <p className="text-sm text-gray-600">
                          {request.collections[0].vehicles.vehicle_type}
                        </p>
                      </div>
                    </div>
                  )}

                  {request.collections[0].drivers && (
                    <div className="flex items-start gap-3">
                      <AlertCircle className="h-5 w-5 text-blue-600 mt-0.5" />
                      <div>
                        <p className="text-sm font-medium text-gray-500">Assigned Driver</p>
                        <p className="text-lg font-semibold">
                          {request.collections[0].drivers.name}
                        </p>
                        <p className="text-sm text-gray-600">
                          📞 {request.collections[0].drivers.phone}
                        </p>
                      </div>
                    </div>
                  )}
                </div>

                <div className="pt-4 border-t">
                  <div className="flex items-start gap-3">
                    <Clock className="h-5 w-5 text-gray-400 mt-0.5" />
                    <div className="flex-1">
                      <p className="text-sm font-medium text-gray-500 mb-2">Collection Status</p>
                      <Badge className="mb-2 capitalize">
                        {request.collections[0].status.replace('_', ' ')}
                      </Badge>
                      
                      {request.collections[0].actual_pickup_time && (
                        <p className="text-sm text-gray-600 mt-2">
                          <strong>Picked up:</strong>{' '}
                          {new Date(request.collections[0].actual_pickup_time).toLocaleString()}
                        </p>
                      )}
                      
                      {request.collections[0].completion_time && (
                        <p className="text-sm text-gray-600">
                          <strong>Completed:</strong>{' '}
                          {new Date(request.collections[0].completion_time).toLocaleString()}
                        </p>
                      )}
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>
          )}

          {/* Waiting for Assignment */}
          {request.status === 'approved' && (!request.collections || request.collections.length === 0) && (
            <Card className="border-blue-200 bg-blue-50">
              <CardContent className="pt-6">
                <div className="flex items-center gap-3">
                  <Clock className="h-5 w-5 text-blue-600 animate-pulse" />
                  <div>
                    <p className="font-semibold text-blue-900">Awaiting Assignment</p>
                    <p className="text-sm text-blue-700">
                      Your request has been approved. A vehicle and driver will be assigned soon.
                    </p>
                  </div>
                </div>
              </CardContent>
            </Card>
          )}

          {/* Actions */}
          <div className="flex gap-4">
            <Link href="/collections" className="flex-1">
              <Button variant="outline" className="w-full">
                Back to All Requests
              </Button>
            </Link>
            {canCancel && (
              <Button
                variant="destructive"
                onClick={handleCancel}
                disabled={cancelling}
                className="flex-1 gap-2"
              >
                <Trash2 className="h-4 w-4" />
                {cancelling ? 'Cancelling...' : 'Cancel Request'}
              </Button>
            )}
          </div>
        </div>
      </main>
    </div>
  )
}
