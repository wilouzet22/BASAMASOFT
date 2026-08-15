import math

start_x = 986.5
start_y = 1084.0
end_x = 969.3
end_y = 252.0
num_points = 40

print("$refPoints = [")
for i in range(num_points):
    t = i / (num_points - 1)
    
    y = start_y + (end_y - start_y) * t
    base_x = start_x + (end_x - start_x) * t
    
    # 3 half-waves
    curve_offset = math.sin(t * 3 * math.pi) * 75
    
    x = base_x + curve_offset
    
    label = "Base" if i == 0 else ("Cima" if i == num_points - 1 else "")
    comment = f"// Semana {i+1}" + (f" ({label})" if label else "")
    
    print(f"        {i+1:<2} => ['x' => {x:.2f}, 'y' => {y:.2f}], {comment}")
print("    ];")
