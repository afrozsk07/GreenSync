'use client'

import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { BarChart, Bar, LineChart, Line, PieChart, Pie, Cell, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer } from 'recharts'

interface ChartData {
  requestsByStatus: Array<{ name: string; value: number }>
  requestsByType: Array<{ name: string; value: number }>
  requestsOverTime: Array<{ date: string; count: number }>
}

interface AdminChartsProps {
  data: ChartData
}

const COLORS = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6']

export function AdminCharts({ data }: AdminChartsProps) {
  return (
    <div className="grid md:grid-cols-2 gap-6 mt-8">
      {/* Requests by Status - Pie Chart */}
      <Card>
        <CardHeader>
          <CardTitle>Requests by Status</CardTitle>
          <CardDescription>Distribution of collection requests</CardDescription>
        </CardHeader>
        <CardContent>
          <ResponsiveContainer width="100%" height={300}>
            <PieChart>
              <Pie
                data={data.requestsByStatus}
                cx="50%"
                cy="50%"
                labelLine={false}
                label={({ name, percent }) => `${name}: ${((percent || 0) * 100).toFixed(0)}%`}
                outerRadius={80}
                fill="#8884d8"
                dataKey="value"
              >
                {data.requestsByStatus.map((entry, index) => (
                  <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                ))}
              </Pie>
              <Tooltip />
            </PieChart>
          </ResponsiveContainer>
        </CardContent>
      </Card>

      {/* Requests by Waste Type - Bar Chart */}
      <Card>
        <CardHeader>
          <CardTitle>Requests by Waste Type</CardTitle>
          <CardDescription>Most common waste types</CardDescription>
        </CardHeader>
        <CardContent>
          <ResponsiveContainer width="100%" height={300}>
            <BarChart data={data.requestsByType}>
              <CartesianGrid strokeDasharray="3 3" />
              <XAxis dataKey="name" />
              <YAxis />
              <Tooltip />
              <Bar dataKey="value" fill="#10b981" />
            </BarChart>
          </ResponsiveContainer>
        </CardContent>
      </Card>

      {/* Requests Over Time - Line Chart */}
      <Card className="md:col-span-2">
        <CardHeader>
          <CardTitle>Requests Over Time</CardTitle>
          <CardDescription>Collection requests trend (last 7 days)</CardDescription>
        </CardHeader>
        <CardContent>
          <ResponsiveContainer width="100%" height={300}>
            <LineChart data={data.requestsOverTime}>
              <CartesianGrid strokeDasharray="3 3" />
              <XAxis dataKey="date" />
              <YAxis />
              <Tooltip />
              <Legend />
              <Line type="monotone" dataKey="count" stroke="#10b981" strokeWidth={2} name="Requests" />
            </LineChart>
          </ResponsiveContainer>
        </CardContent>
      </Card>
    </div>
  )
}
